<?php

namespace Tests\Feature;

use App\Mail\DiscoverySessionRegistered;
use App\Mail\DiscoverySessionStaffNotification;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Database\Seeders\DiscoverySessionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Registering for the Community Discovery Session.
 *
 * The first Skills Co-op event with a door, a postcode and a fire limit, which
 * is what makes it different from the online panels. The things worth pinning
 * down are the ones that would embarrass us in a room: telling somebody the
 * wrong time, losing an access requirement, or turning a person away from a
 * free event because a counter said 35.
 */
class DiscoverySessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DiscoverySessionSeeder::class);
        Mail::fake();
    }

    private function event(): PanelSession
    {
        return PanelSession::where('slug', 'discovery-session')->firstOrFail();
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Bola',
            'last_name'  => 'Soko',
            'email'      => 'bola@example.com',
            'phone'      => '07700 900123',
            'audience_group' => 'returning',
            'notes'      => '',
            'consent'    => '1',
        ], $overrides);
    }

    /**
     * Eventbrite runs alongside the site's own form, not instead of it.
     *
     * The site's form is the primary route because it needs no account and no
     * redirect off the site, but plenty of people would rather use the thing
     * they already have a login for, and refusing them that costs a
     * registration. The link opens in a new tab with rel=noopener so leaving is
     * not a one-way door.
     */
    public function test_the_page_offers_eventbrite_as_an_alternative(): void
    {
        $this->get('/discovery-session')
            ->assertOk()
            ->assertSee('Prefer Eventbrite?')
            ->assertSee('eventbrite.co.uk/e/1996441615615', false)
            ->assertSee('rel="noopener"', false);
    }

    /**
     * With no Eventbrite listing there is no half-sentence pointing nowhere.
     */
    public function test_the_eventbrite_line_disappears_when_there_is_no_listing(): void
    {
        $this->event()->update(['eventbrite_url' => null]);

        $this->get('/discovery-session')
            ->assertOk()
            ->assertDontSee('Prefer Eventbrite?');
    }

    /**
     * Fill the room, so the next registration has to be a waitlist one.
     */
    private function fillTheRoom(): void
    {
        $session = $this->event();

        for ($i = 0; $i < $session->capacity; $i++) {
            SessionRegistration::create([
                'panel_session_id' => $session->id,
                'first_name'       => 'Person',
                'last_name'        => (string) $i,
                'email'            => "person{$i}@example.com",
                'interest_type'    => 'learner',
                'waitlisted'       => false,
            ]);
        }
    }

    // ── The page ─────────────────────────────────────────────────────────────

    public function test_the_page_shows_what_somebody_needs_to_get_there(): void
    {
        $this->get('/discovery-session')
            ->assertOk()
            ->assertSee('Community Discovery Session')
            ->assertSee('Wirral Multicultural Organisation')
            ->assertSee('111 Conway Street, Birkenhead, CH41 4AF')
            ->assertSee('Step-free');
    }

    /**
     * event_date holds UK wall-clock time across this table and every panel
     * view formats it straight. Converting it would read 12.30 as UTC and print
     * 1.30pm, telling people to arrive an hour after the doors open.
     */
    public function test_the_time_is_not_shifted_by_a_timezone_conversion(): void
    {
        $this->get('/discovery-session')
            ->assertOk()
            ->assertSee('12.30pm')
            ->assertSee('3.30pm')
            ->assertDontSee('1.30pm')
            ->assertDontSee('4.30pm');
    }

    /**
     * The five pathways as announced, which are not yet what the Pathway table
     * says. Until the taxonomy is reconciled the event page reads the config.
     */
    public function test_the_page_lists_the_five_announced_pathways(): void
    {
        $response = $this->get('/discovery-session')->assertOk();

        foreach (config('organisation.pathways') as $pathway) {
            $response->assertSee($pathway);
        }
    }

    /**
     * Two URLs for one event would compete in search and split its links.
     */
    public function test_the_generic_session_url_defers_to_the_dedicated_page(): void
    {
        $this->get('/sessions/discovery-session')
            ->assertStatus(301)
            ->assertRedirect('/discovery-session');
    }

    // ── Registering ──────────────────────────────────────────────────────────

    public function test_it_registers_somebody_and_emails_them(): void
    {
        $this->post('/discovery-session', $this->validPayload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $registration = SessionRegistration::firstOrFail();

        $this->assertSame('Bola', $registration->first_name);
        $this->assertSame('Soko', $registration->last_name);
        $this->assertSame('Bola Soko', $registration->name, 'name is composed so the admin list and CSV keep working.');
        $this->assertSame('07700 900123', $registration->phone);
        $this->assertFalse($registration->waitlisted);
        $this->assertNotNull($registration->consented_at);

        Mail::assertSent(DiscoverySessionRegistered::class,
            fn ($mail) => $mail->hasTo('bola@example.com'));
        Mail::assertSent(DiscoverySessionStaffNotification::class,
            fn ($mail) => $mail->hasTo(config('organisation.email')));

        // Stamped only after a send that did not throw, so a null here always
        // means somebody was told they had a place and never got it in
        // writing. discovery:confirmations finds and repairs exactly those.
        $this->assertNotNull($registration->fresh()->confirmation_sent_at);
    }

    /**
     * A confirmation that fails to send must not look like one that succeeded.
     *
     * Sending is non-fatal on purpose: the registration is already saved, and a
     * mail server having a bad afternoon should not become a 500 for someone
     * who arrived from a flyer. The price is that the failure is silent, so the
     * only thing standing between that and a person turning up to an event
     * nobody told them about is this column.
     */
    public function test_a_failed_confirmation_leaves_the_registration_marked_unsent(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('mail server down'));

        $this->post('/discovery-session', $this->validPayload())
            ->assertSessionHasNoErrors();

        $registration = SessionRegistration::firstOrFail();

        $this->assertNull($registration->confirmation_sent_at);
        $this->assertTrue(
            SessionRegistration::awaitingConfirmation()->whereKey($registration->id)->exists(),
            'The repair command has to be able to find them.'
        );
    }

    /**
     * Consent that defaults to true is not consent. An unticked box is absent
     * from the request entirely, which is why the rule is "accepted".
     */
    public function test_it_refuses_to_register_anyone_who_did_not_consent(): void
    {
        $payload = $this->validPayload();
        unset($payload['consent']);

        $this->post('/discovery-session', $payload)
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, SessionRegistration::count());
        Mail::assertNothingSent();
    }

    public function test_it_requires_a_name_and_an_email(): void
    {
        $this->post('/discovery-session', ['consent' => '1'])
            ->assertSessionHasErrors(['first_name', 'last_name', 'email']);

        $this->assertSame(0, SessionRegistration::count());
    }

    /**
     * Registering twice updates the entry rather than making a second one, so
     * one person cannot silently occupy two of thirty-five places.
     */
    public function test_registering_twice_updates_rather_than_duplicates(): void
    {
        $this->post('/discovery-session', $this->validPayload());
        $this->post('/discovery-session', $this->validPayload(['phone' => '07700 900999']));

        $this->assertSame(1, SessionRegistration::count());
        $this->assertSame('07700 900999', SessionRegistration::first()->phone);
        $this->assertSame(1, $this->event()->confirmedCount());
    }

    /**
     * An access requirement is the whole reason the notes field exists, so it
     * has to survive the round trip and reach a human.
     */
    public function test_an_access_requirement_is_kept_and_sent_to_staff(): void
    {
        $this->post('/discovery-session', $this->validPayload([
            'notes' => 'I use a wheelchair and will bring a support worker.',
        ]));

        $this->assertStringContainsString('wheelchair', SessionRegistration::first()->notes);

        Mail::assertSent(DiscoverySessionStaffNotification::class, function ($mail) {
            return str_contains($mail->render(), 'wheelchair')
                && str_contains($mail->build()->subject, 'has a note');
        });
    }

    // ── Capacity ─────────────────────────────────────────────────────────────

    public function test_it_waitlists_rather_than_turns_people_away_once_full(): void
    {
        $this->fillTheRoom();
        $this->assertTrue($this->event()->isFull());

        $this->post('/discovery-session', $this->validPayload())
            ->assertSessionHas('waitlisted', true);

        $registration = SessionRegistration::where('email', 'bola@example.com')->firstOrFail();

        $this->assertTrue($registration->waitlisted);
        Mail::assertSent(DiscoverySessionRegistered::class);
    }

    /**
     * Recomputing from capacity on every save would push a confirmed attendee
     * onto the waiting list behind people who registered after them, purely for
     * correcting their own phone number.
     */
    public function test_someone_already_holding_a_place_keeps_it_when_they_re_register(): void
    {
        $this->post('/discovery-session', $this->validPayload());

        // Everyone else arrives and fills what is left.
        $session = $this->event();
        for ($i = 0; $i < $session->capacity - 1; $i++) {
            SessionRegistration::create([
                'panel_session_id' => $session->id,
                'first_name'       => 'Person',
                'last_name'        => (string) $i,
                'email'            => "person{$i}@example.com",
                'interest_type'    => 'learner',
                'waitlisted'       => false,
            ]);
        }
        $this->assertTrue($this->event()->isFull());

        $this->post('/discovery-session', $this->validPayload(['phone' => '07700 900555']));

        $this->assertFalse(SessionRegistration::where('email', 'bola@example.com')->first()->waitlisted);
    }

    public function test_the_page_offers_the_waiting_list_once_the_room_is_full(): void
    {
        $this->fillTheRoom();

        $this->get('/discovery-session')
            ->assertOk()
            ->assertSee('The room is full')
            ->assertSee('Join the waiting list');
    }

    /**
     * The count is never published, at any number.
     *
     * It is a scarcity device, and this is a free event for people who have
     * reason to feel they are competing for something they might not deserve.
     * It also invites the question of what number you are, which is nobody's
     * business but ours. Staff still see it; registrants never do.
     */
    public function test_it_never_tells_registrants_how_many_places_are_left(): void
    {
        $session = $this->event();

        // Nearly full, which is exactly when a countdown would be tempting.
        for ($i = 0; $i < $session->capacity - 4; $i++) {
            SessionRegistration::create([
                'panel_session_id' => $session->id,
                'first_name'       => 'Person',
                'last_name'        => (string) $i,
                'email'            => "person{$i}@example.com",
                'interest_type'    => 'learner',
                'waitlisted'       => false,
            ]);
        }

        $html = $this->get('/discovery-session')->assertOk()
            ->assertDontSee('places left')
            ->assertDontSee('place left')
            ->getContent();

        // Nor the capacity itself, which would let anyone work it out.
        $this->assertStringNotContainsString('of '.$session->capacity, $html);

        // The number is still there for the people who run the room.
        $this->assertSame(4, $this->event()->spacesLeft());
    }

    /**
     * Capacity can be lowered after people have registered. A negative number
     * on a public page reads as a bug rather than as "we are full".
     */
    public function test_spaces_left_never_goes_negative(): void
    {
        $this->fillTheRoom();
        $this->event()->update(['capacity' => 10]);

        $this->assertSame(0, $this->event()->spacesLeft());
        $this->assertTrue($this->event()->isFull());
    }

    // ── The banner ───────────────────────────────────────────────────────────

    /**
     * The class attributes of the hero slides, in document order.
     *
     * Counting the raw string would also count the stylesheet, which names
     * .ath-hero-content a dozen times.
     */
    private function slideClasses(string $html): array
    {
        preg_match_all('/class="(ath-hero-content[^"]*)"/', $html, $m);

        return $m[1];
    }

    private function dotClasses(string $html): array
    {
        preg_match_all('/class="(ath-dot[^"]*)"/', $html, $m);

        return $m[1];
    }

    /**
     * The controls are operable, and they say what they do.
     *
     * They were unlabelled <span>s: no keyboard could reach them and a screen
     * reader had nothing to announce. That was a gap before; it matters more
     * now, because under prefers-reduced-motion the deck does not advance on
     * its own and these are the only way to see the other slides at all.
     */
    public function test_the_carousel_controls_are_reachable_and_named(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match_all('/<button[^>]*class="ath-dot[^"]*"[^>]*>/', $html, $m);
        $dotButtons = $m[0];

        // A button per slide, not a span per slide.
        $this->assertCount(count($this->slideClasses($html)), $dotButtons);

        foreach ($dotButtons as $button) {
            $this->assertMatchesRegularExpression('/aria-label="[^"]{8,}"/', $button);
        }

        // The names describe the slide rather than its position, so "button 3
        // of 4" is not the whole of what a listener gets.
        $this->assertStringContainsString('aria-label="Community Discovery Session, 29 August"', $html);

        // Exactly one is current, and it is the first.
        $this->assertSame(1, substr_count($html, 'aria-current="true"'));
        $this->assertStringContainsString('aria-current="true"', $dotButtons[0]);
    }

    /**
     * WCAG 2.2.2 wants a mechanism to stop moving content. Hovering and tabbing
     * both hold the deck, but neither is a control somebody can find on
     * purpose, so there is a real one.
     */
    public function test_the_carousel_offers_a_pause_control(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('ath-slider-toggle', false)
            ->assertSee('aria-label="Pause the slideshow"', false);
    }

    public function test_the_event_leads_the_home_carousel(): void
    {
        $html = $this->get('/')->assertOk()
            ->assertSee('Free community event')
            ->assertSee('Community Discovery Session')
            ->assertSee('/discovery-session')
            ->getContent();

        $slides = $this->slideClasses($html);

        // First in the deck and already showing. A slide the visitor only sees
        // after waiting six seconds is not leading anything.
        $this->assertStringContainsString('ath-hero-event', $slides[0]);
        $this->assertStringContainsString('active', $slides[0]);

        // And it is the only one showing.
        $this->assertSame(1, count(array_filter($slides, fn ($c) => str_contains($c, 'active'))));
    }

    /**
     * The slider indexes slides and dots positionally, so a dot without a
     * slide behind it throws on click.
     */
    public function test_the_dots_match_the_slide_count(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertCount(4, $this->slideClasses($html));
        $this->assertCount(4, $this->dotClasses($html));
    }

    /**
     * Exactly one h1, and it is the page's own rather than the rotating event.
     */
    public function test_the_event_slide_does_not_add_a_second_h1(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<h2 class="ath-title ath-title-lead">Community Discovery Session</h2>', $html);
    }

    /**
     * A carousel advertising a date that has passed is worse than no slide, and
     * the deck has to close back up rather than leave a dot with nothing behind
     * it.
     */
    public function test_the_deck_returns_to_three_slides_after_the_event(): void
    {
        $this->event()->update(['event_date' => now()->subDays(2)]);

        $html = $this->get('/')->assertOk()
            ->assertDontSee('Free community event')
            ->getContent();

        $slides = $this->slideClasses($html);

        $this->assertCount(3, $slides);
        $this->assertCount(3, $this->dotClasses($html));

        // The mission slide takes the lead back, rather than the deck opening
        // on nothing.
        $this->assertStringContainsString('active', $slides[0]);
        $this->assertStringNotContainsString('ath-hero-event', $slides[0]);
    }

    public function test_the_event_slide_stays_up_on_the_day_itself(): void
    {
        $this->event()->update(['event_date' => now()->startOfDay()->addHours(2)]);

        $this->get('/')->assertOk()->assertSee('Free community event');
    }

    // ── Spam ─────────────────────────────────────────────────────────────────

    /**
     * The honeypot answers as though it worked. Telling a bot it was detected
     * only tells whoever wrote it what to change.
     */
    public function test_the_honeypot_swallows_the_submission_silently(): void
    {
        $this->post('/discovery-session', $this->validPayload(['website' => 'http://spam.example']))
            ->assertSessionHas('success');

        $this->assertSame(0, SessionRegistration::count());
        Mail::assertNothingSent();
    }
}
