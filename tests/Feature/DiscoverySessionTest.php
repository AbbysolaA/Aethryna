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
     * Scarcity is only worth mentioning when it is real. "30 places left" of 35
     * says the room is empty, which is a reason not to come.
     */
    public function test_it_only_counts_down_when_the_number_is_small(): void
    {
        $this->assertFalse($this->event()->shouldShowSpacesLeft());

        $session = $this->event();
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

        $this->assertTrue($this->event()->shouldShowSpacesLeft());
        $this->get('/discovery-session')->assertSee('4 places left');
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

    public function test_the_home_page_carries_the_banner(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Free community event')
            ->assertSee('Community Discovery Session')
            ->assertSee('/discovery-session')
            ->assertSee('images/logo_white.png');
    }

    /**
     * A banner advertising a date that has passed is worse than no banner.
     */
    public function test_the_banner_takes_itself_down_after_the_event(): void
    {
        $this->event()->update(['event_date' => now()->subDays(2)]);

        $this->get('/')->assertOk()->assertDontSee('Free community event');
    }

    public function test_the_banner_stays_up_on_the_day_itself(): void
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
