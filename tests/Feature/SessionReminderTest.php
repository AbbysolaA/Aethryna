<?php

namespace Tests\Feature;

use App\Mail\SessionRegisteredMail;
use App\Mail\SessionReminder;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The attendance machinery that replaced Eventbrite: reminders a week out
 * and the day before, a calendar file, and the panel page as the link that
 * circulates.
 */
class SessionReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->travelTo('2026-10-12 12:00:00');
    }

    private function panel(array $overrides = []): PanelSession
    {
        return PanelSession::create(array_merge([
            'title'       => 'The Skills Co-op Sessions: Panel 4',
            'slug'        => 'panel-4',
            'tagline'     => 'Panel 4 · Vibe coding: show and tell',
            'description' => 'Real workflows, real prompts, real mistakes.',
            'event_date'  => '2026-10-20 18:30:00',
            'format'      => 'Online',
            'status'      => 'upcoming',
            'sort_order'  => 4,
        ], $overrides));
    }

    private function registrant(PanelSession $panel, string $email, array $overrides = []): SessionRegistration
    {
        $registration = SessionRegistration::create(array_merge([
            'panel_session_id' => $panel->id,
            'name'             => 'Rae Registrant',
            'email'            => $email,
            'interest_type'    => 'learner',
        ], $overrides));

        // Registered well before the reminder windows unless a test says
        // otherwise: the week nudge deliberately skips brand-new sign-ups.
        $registration->forceFill(['created_at' => now()->subDays(3)])->save();

        return $registration;
    }

    public function test_the_week_reminder_goes_once_and_skips_fresh_registrations(): void
    {
        $panel = $this->panel(); // 8 days out
        $old = $this->registrant($panel, 'old@example.com');
        $fresh = $this->registrant($panel, 'fresh@example.com');
        // Fresh relative to the in-window run below, not to today: they sign
        // up two hours before the scheduler fires on the 14th.
        $fresh->forceFill(['created_at' => '2026-10-14 15:40:00'])->save();

        // Outside the window: nothing.
        $this->artisan('sessions:remind')->assertSuccessful();
        Mail::assertNotSent(SessionReminder::class);

        // Six days out: the week reminder, to the older registrant only.
        $this->travelTo('2026-10-14 17:40:00');
        $this->artisan('sessions:remind')->assertSuccessful();

        Mail::assertSent(SessionReminder::class, 1);
        Mail::assertSent(SessionReminder::class, fn ($m) => $m->hasTo('old@example.com') && $m->stage === 'week');
        $this->assertNotNull($old->fresh()->reminded_week_at);

        // Running again sends nothing more.
        $this->artisan('sessions:remind')->assertSuccessful();
        Mail::assertSent(SessionReminder::class, 1);
    }

    public function test_the_day_reminder_wins_close_to_the_event_and_goes_once(): void
    {
        $panel = $this->panel();
        $this->registrant($panel, 'rae@example.com');

        $this->travelTo('2026-10-19 17:40:00'); // the day before
        $this->artisan('sessions:remind')->assertSuccessful();

        Mail::assertSent(SessionReminder::class, 1);
        Mail::assertSent(SessionReminder::class, fn ($m) => $m->hasTo('rae@example.com') && $m->stage === 'day');

        $this->artisan('sessions:remind')->assertSuccessful();
        Mail::assertSent(SessionReminder::class, 1);
    }

    public function test_past_panels_remind_nobody(): void
    {
        $panel = $this->panel(['event_date' => '2026-10-10 18:30:00', 'status' => 'past']);
        $this->registrant($panel, 'rae@example.com');

        $this->artisan('sessions:remind')->assertSuccessful();

        Mail::assertNotSent(SessionReminder::class);
    }

    public function test_the_reminder_email_carries_the_essentials(): void
    {
        $panel = $this->panel();

        $mail = new SessionReminder($panel, 'Rae Registrant', 'day');
        $built = $mail->build();

        $this->assertSame('Tomorrow: Panel 4 · Vibe coding: show and tell', $built->subject);
        $this->assertTrue($built->hasReplyTo(config('organisation.email')));

        $html = $built->render();
        $this->assertStringContainsString('See you tomorrow', $html);
        $this->assertStringContainsString('Tuesday 20 October 2026', $html);
        $this->assertStringContainsString('/sessions/panel-4/calendar', $html);

        // The calendar file rides along.
        $this->assertCount(1, $built->rawAttachments);
        $this->assertSame('panel-4.ics', $built->rawAttachments[0]['name']);
    }

    public function test_the_calendar_download_is_a_real_ics_in_real_time(): void
    {
        $panel = $this->panel();

        $response = $this->get('/sessions/panel-4/calendar')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8');

        $ics = $response->getContent();

        $this->assertStringContainsString('BEGIN:VEVENT', $ics);
        $this->assertStringContainsString('SUMMARY:Panel 4 · Vibe coding: show and tell', $ics);
        // 18:30 UK wall clock on 20 October is British Summer Time: 17:30 UTC.
        $this->assertStringContainsString('DTSTART:20261020T173000Z', $ics);
        $this->assertStringContainsString('UID:panel-4@skillscoop.org', $ics);

        // No date, no calendar entry.
        $undated = $this->panel(['slug' => 'panel-5', 'event_date' => null, 'title' => 'Panel 5', 'tagline' => 'Panel 5']);
        $this->get('/sessions/panel-5/calendar')->assertNotFound();
    }

    public function test_the_confirmation_email_gained_the_calendar_and_the_invite_line(): void
    {
        $panel = $this->panel();

        $mail = new SessionRegisteredMail($panel, 'Rae Registrant');
        $built = $mail->build();

        $this->assertSame('You are registered: Panel 4 · Vibe coding: show and tell', $built->subject);

        $html = $built->render();
        $this->assertStringContainsString('Add to calendar', $html);
        $this->assertStringContainsString('/sessions/panel-4/calendar', $html);
        $this->assertStringContainsString('bring someone', $html);
        $this->assertStringContainsString('/sessions/panel-4', $html);

        $this->assertCount(1, $built->rawAttachments);
        $this->assertSame('panel-4.ics', $built->rawAttachments[0]['name']);
    }

    public function test_the_panel_page_offers_calendar_and_share(): void
    {
        $this->panel();

        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('Add to calendar')
            ->assertSee('/sessions/panel-4/calendar', false)
            ->assertSee('Know someone who should be in the room?')
            ->assertSee('Copy link')
            ->assertSee('Invite a friend');
    }

    public function test_the_sessions_index_register_cta_links_to_the_panel_page(): void
    {
        $panel = $this->panel();

        $this->get('/sessions')
            ->assertOk()
            ->assertSee(route('sessions.show', $panel), false);
    }
    /**
     * The joining link rides every email once it is set: the day reminder,
     * the confirmation and the calendar entry all point at the stream.
     */
    public function test_the_joining_link_reaches_every_email_and_the_calendar(): void
    {
        $panel = $this->panel();
        $panel->update(['join_url' => 'https://streamyard.com/watch/DeQpDvThNZnq']);

        $day = (new SessionReminder($panel, 'Rae Registrant', 'day'))->build();
        $dayHtml = $day->render();
        $this->assertStringContainsString('Join the session', $dayHtml);
        $this->assertStringContainsString('streamyard.com/watch/DeQpDvThNZnq', $dayHtml);

        $confirmation = (new SessionRegisteredMail($panel, 'Rae Registrant'))->build()->render();
        $this->assertStringContainsString('Join the session', $confirmation);
        $this->assertStringContainsString('streamyard.com/watch/DeQpDvThNZnq', $confirmation);
        $this->assertStringContainsString('keep this email', $confirmation);

        $this->assertStringContainsString('URL:https://streamyard.com/watch/DeQpDvThNZnq', $panel->toIcs());

        // And the event page's schema names the stream as the location.
        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('"url":"https://streamyard.com/watch/DeQpDvThNZnq"', false);
    }

    /**
     * A day-stage reminder sent on the event day itself says today, not
     * tomorrow: late registrants are caught by the same daily run.
     */
    public function test_the_day_reminder_says_today_on_the_day(): void
    {
        $panel = $this->panel();

        $this->travelTo('2026-10-20 12:00:00');
        $built = (new SessionReminder($panel, 'Rae Registrant', 'day'))->build();

        $this->assertSame('Today: Panel 4 · Vibe coding: show and tell', $built->subject);
        $this->assertStringContainsString('See you today', $built->render());
    }
}
