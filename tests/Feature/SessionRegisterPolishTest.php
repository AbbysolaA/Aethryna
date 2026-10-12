<?php

namespace Tests\Feature;

use App\Mail\SessionRegisteredMail;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The registration form speaks to attendees, not only to people shopping
 * for the programme, the follow links appear where goodwill peaks, and
 * every page declares the favicon instead of hoping the browser guesses.
 */
class SessionRegisterPolishTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->travelTo('2026-10-12 12:00:00');
    }

    private function panel(): PanelSession
    {
        return PanelSession::create([
            'title'       => 'The Skills Co-op Sessions: Panel 4',
            'slug'        => 'panel-4',
            'tagline'     => 'Panel 4 · Vibe coding: show and tell',
            'description' => 'Real workflows.',
            'event_date'  => '2026-10-20 18:30:00',
            'status'      => 'upcoming',
            'sort_order'  => 4,
        ]);
    }

    public function test_someone_can_register_simply_as_an_attendee(): void
    {
        $panel = $this->panel();

        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('An attendee, here for the panel');

        $this->post('/sessions/register', [
            'name'             => 'Rae Registrant',
            'email'            => 'rae@example.com',
            'interest_type'    => 'attendee',
            'panel_session_id' => $panel->id,
        ])->assertSessionHasNoErrors();

        $registration = SessionRegistration::firstOrFail();
        $this->assertSame('attendee', $registration->interest_type);
        $this->assertSame('Attendee', $registration->interestLabel());
    }

    public function test_the_thank_you_state_offers_the_follow_links(): void
    {
        $this->panel();

        $this->withSession(['success' => 'You are registered.'])
            ->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('And follow us between sessions')
            ->assertSee('linkedin.com/company/theskillscoop', false)
            ->assertSee('instagram.com/skillsco_op', false)
            ->assertSee('tiktok.com/@skillsco_op', false);
    }

    public function test_the_confirmation_email_carries_the_follow_links(): void
    {
        $panel = $this->panel();

        $html = (new SessionRegisteredMail($panel, 'Rae Registrant'))->build()->render();

        $this->assertStringContainsString('come and say hello', $html);
        $this->assertStringContainsString('linkedin.com/company/theskillscoop', $html);
        $this->assertStringContainsString('instagram.com/skillsco_op', $html);
        $this->assertStringContainsString('tiktok.com/@skillsco_op', $html);
    }

    public function test_every_layout_declares_the_favicon(): void
    {
        $this->panel();

        // The public layout.
        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('favicon-32x32.png', false);

        // The admin layout wraps the dashboard views.
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.speaker-applications.index'))
            ->assertOk()
            ->assertSee('favicon-32x32.png', false);
    }
    /**
     * The footer icons derive from the same config list, so a dead handle
     * cannot linger there while the config says something else.
     */
    public function test_the_footer_carries_the_live_social_handles(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('instagram.com/skillsco_op', false)
            ->assertSee('tiktok.com/@skillsco_op', false)
            ->assertSee('youtube.com/@TheSkillsCoOpSessions', false)
            ->assertDontSee('aethrynafoundation')
            ->assertDontSee('tiktok.com/@aethryna', false);
    }
    /**
     * The team hears about each new registration, reply-to the registrant,
     * and a re-submission updates quietly instead of announcing twice.
     */
    public function test_a_new_registration_notifies_the_team_once(): void
    {
        $panel = $this->panel();

        $register = fn () => $this->post('/sessions/register', [
            'name'             => 'Rae Registrant',
            'email'            => 'rae@example.com',
            'interest_type'    => 'attendee',
            'referral_source'  => 'social_media',
            'wants_to_speak'   => '1',
            'speaker_topic'    => 'Shipping with AI on a team of one',
            'panel_session_id' => $panel->id,
        ]);

        $register();

        Mail::assertSent(\App\Mail\SessionRegistrationReceived::class, 1);
        Mail::assertSent(\App\Mail\SessionRegistrationReceived::class, function ($mail) {
            $mail->build();

            return $mail->hasTo(config('organisation.email'))
                && $mail->hasReplyTo('rae@example.com');
        });

        // Registering again updates the entry without a second announcement.
        $register();
        Mail::assertSent(\App\Mail\SessionRegistrationReceived::class, 1);
    }

    public function test_the_notification_carries_the_registration_details(): void
    {
        $panel = $this->panel();

        $registration = \App\Models\SessionRegistration::create([
            'panel_session_id' => $panel->id,
            'name'             => 'Rae Registrant',
            'email'            => 'rae@example.com',
            'interest_type'    => 'attendee',
            'referral_source'  => 'social_media',
            'wants_to_speak'   => true,
            'speaker_topic'    => 'Shipping with AI on a team of one',
        ]);

        $html = (new \App\Mail\SessionRegistrationReceived($registration))->build()->render();

        $this->assertStringContainsString('Rae Registrant', $html);
        $this->assertStringContainsString('Attendee', $html);
        $this->assertStringContainsString('Social media', $html);
        $this->assertStringContainsString('Offered to speak', $html);
        $this->assertStringContainsString('Shipping with AI on a team of one', $html);
        $this->assertStringContainsString('1 registration', $html);
    }

    /**
     * A replaced banner must change its URL, or browsers and the Cloudflare
     * edge keep serving the old image, which is exactly what happened when
     * the landscape banner replaced the square one under the same name.
     */
    public function test_the_banner_url_is_versioned_by_file_change(): void
    {
        // panel-4.jpg is committed to the repo, so its mtime is real.
        $panel = $this->panel();
        $panel->forceFill(['banner_path' => 'panel-4.jpg'])->save();

        $this->assertStringContainsString('?v=', $panel->bannerUrl());
    }
}
