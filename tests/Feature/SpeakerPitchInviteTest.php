<?php

namespace Tests\Feature;

use App\Mail\SpeakerPitchInvite;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use App\Models\SpeakerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The tick on the registration form now leads somewhere: a follow-up email
 * with the pitch link, sent once per person ever, with a command to catch
 * the people who ticked it before the follow-up existed. And the pitch
 * itself now collects social handles, so speakers can be tagged when a
 * lineup is announced.
 */
class SpeakerPitchInviteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->travelTo('2026-11-02 12:00:00');
    }

    private function panel(array $overrides = []): PanelSession
    {
        return PanelSession::create(array_merge([
            'title'       => 'The Skills Co-op Sessions: Panel 4b',
            'slug'        => 'founders-build-live',
            'tagline'     => 'Vibe Coding: Founders Build Live',
            'description' => 'Founders build live.',
            'event_date'  => '2026-11-10 18:30:00',
            'status'      => 'upcoming',
            'sort_order'  => 5,
        ], $overrides));
    }

    private function register(array $overrides = []): void
    {
        $this->post('/sessions/register', array_merge([
            'name'          => 'Leila Boughadou',
            'email'         => 'leila@example.com',
            'interest_type' => 'learner',
        ], $overrides));
    }

    public function test_offering_to_speak_sends_the_pitch_invite(): void
    {
        $this->panel();

        $this->register([
            'wants_to_speak' => '1',
            'speaker_topic'  => 'Shipping a product with no engineers',
        ]);

        Mail::assertSent(SpeakerPitchInvite::class, function ($mail) {
            $mail->build();
            $html = $mail->render();

            return $mail->hasTo('leila@example.com')
                && $mail->hasReplyTo(config('organisation.email'))
                && str_contains($html, 'https://skillscoop.org/apply-to-speak')
                && str_contains($html, 'Shipping a product with no engineers')
                && ! str_contains($html, 'localhost');
        });

        $this->assertNotNull(
            SessionRegistration::where('email', 'leila@example.com')->first()->speaker_invite_sent_at
        );
    }

    public function test_no_invite_without_the_tick_and_none_twice(): void
    {
        $panelA = $this->panel();

        $this->register();
        Mail::assertNotSent(SpeakerPitchInvite::class);

        // Ticks on a later panel's form; the invite goes once.
        $panelB = $this->panel(['slug' => 'panel-5', 'sort_order' => 6]);
        $this->register(['wants_to_speak' => '1', 'panel_session_id' => $panelA->id]);
        $this->register(['wants_to_speak' => '1', 'panel_session_id' => $panelB->id]);

        Mail::assertSent(SpeakerPitchInvite::class, 1);
    }

    public function test_the_backlog_command_invites_only_the_unstamped(): void
    {
        $panel = $this->panel();

        // Offered before the follow-up existed: no stamp.
        SessionRegistration::create([
            'panel_session_id' => $panel->id,
            'name'             => 'Leila Boughadou',
            'email'            => 'Leila@Example.com',
            'interest_type'    => 'learner',
            'wants_to_speak'   => true,
            'speaker_topic'    => 'Founding with AI tools',
        ]);
        // Same person, second row, different casing: one email, both stamped.
        SessionRegistration::create([
            'panel_session_id' => null,
            'name'             => 'Leila Boughadou',
            'email'            => 'leila@example.com',
            'interest_type'    => 'curious',
            'wants_to_speak'   => true,
        ]);
        // Never offered: left alone.
        SessionRegistration::create([
            'panel_session_id' => $panel->id,
            'name'             => 'Miki Fitzgerald',
            'email'            => 'miki@example.com',
            'interest_type'    => 'curious',
        ]);
        // Already invited at registration time: left alone.
        SessionRegistration::create([
            'panel_session_id' => $panel->id,
            'name'             => 'Omonigho Okome',
            'email'            => 'omonigho@example.com',
            'interest_type'    => 'mentor',
            'wants_to_speak'   => true,
            'speaker_invite_sent_at' => now()->subDay(),
        ]);

        $this->artisan('registrations:speaker-invite', ['--dry-run' => true])
            ->expectsOutputToContain('1 would be emailed.')
            ->assertSuccessful();
        Mail::assertNothingSent();

        $this->artisan('registrations:speaker-invite')
            ->expectsOutputToContain('Sent 1 invite.')
            ->assertSuccessful();

        Mail::assertSent(SpeakerPitchInvite::class, 1);
        Mail::assertSent(SpeakerPitchInvite::class, fn ($mail) => $mail->hasTo('Leila@Example.com'));
        $this->assertSame(0, SessionRegistration::where('wants_to_speak', true)
            ->whereNull('speaker_invite_sent_at')->count());

        // Running it again finds nobody.
        $this->artisan('registrations:speaker-invite')
            ->expectsOutputToContain('already been invited')
            ->assertSuccessful();
        Mail::assertSent(SpeakerPitchInvite::class, 1);
    }

    public function test_the_pitch_form_collects_social_handles_and_admin_sees_them(): void
    {
        $this->post('/apply-to-speak', [
            'name'              => 'Leila Boughadou',
            'email'             => 'leila@example.com',
            'bio'               => 'Founder building in public.',
            'talk_title'        => 'From tick to talk',
            'talk_summary'      => 'How a maybe becomes a panel seat.',
            'social_handles'    => 'Instagram @leila.builds, TikTok @leilabuilds',
            'consent'           => '1',
            'recording_consent' => '1',
        ])->assertRedirect(route('speakers.apply.thanks'));

        $this->assertSame(
            'Instagram @leila.builds, TikTok @leilabuilds',
            SpeakerApplication::where('email', 'leila@example.com')->first()->social_handles
        );

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.speaker-applications.index'))
            ->assertOk()
            ->assertSee('Instagram @leila.builds, TikTok @leilabuilds');

        // And the field is on the form itself.
        $this->get('/apply-to-speak')
            ->assertOk()
            ->assertSee('name="social_handles"', false)
            ->assertSee('we can tag you');
    }
}
