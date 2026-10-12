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
            ->assertSee('instagram.com', false);
    }

    public function test_the_confirmation_email_carries_the_follow_links(): void
    {
        $panel = $this->panel();

        $html = (new SessionRegisteredMail($panel, 'Rae Registrant'))->build()->render();

        $this->assertStringContainsString('come and say hello', $html);
        $this->assertStringContainsString('linkedin.com/company/theskillscoop', $html);
        $this->assertStringContainsString('instagram.com', $html);
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
}
