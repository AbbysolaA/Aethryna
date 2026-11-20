<?php

namespace Tests\Feature;

use App\Mail\PanelRecap;
use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use App\Models\PromptUnlock;
use App\Models\SessionPrompt;
use App\Models\SessionRegistration;
use App\Models\User;
use App\Services\EmailOctopusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Viewers can get the prompts behind the live builds: titles in public,
 * the prompts themselves after a name and email, with joining the mailing
 * list a separate choice.
 */
class SessionPromptsTest extends TestCase
{
    use RefreshDatabase;

    private array $subscribed = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-11-20 12:00:00');

        // Record EmailOctopus calls instead of making them.
        $this->subscribed = [];
        $fake = new class($this->subscribed) extends EmailOctopusService {
            public function __construct(private array &$log) {}

            public function subscribe(string $email, array $fields = [], array $tags = []): bool
            {
                $this->log[] = [$email, $tags];

                return true;
            }
        };
        $this->app->instance(EmailOctopusService::class, $fake);
    }

    private function panelWithPrompts(): PanelSession
    {
        $panel = PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 4', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-18 18:30:00', 'status' => 'past', 'sort_order' => 4,
        ]);
        $bola = PanelSpeaker::create(['name' => 'Bola Naomi Soko', 'title' => 'Founder', 'bio' => 'B.']);
        $panel->speakers()->attach($bola->id, ['sort_order' => 1]);

        SessionPrompt::create([
            'panel_session_id' => $panel->id, 'panel_speaker_id' => $bola->id,
            'title' => 'Build the certificate generator', 'body' => "SECRET-PROMPT-BODY\nMake a certificate app.",
            'sort_order' => 1,
        ]);

        return $panel;
    }

    public function test_titles_are_public_and_prompts_are_locked(): void
    {
        $this->panelWithPrompts();

        $this->get('/sessions/founders-build-live')
            ->assertOk()
            ->assertSee('The prompts from the live builds')
            ->assertSee('Build the certificate generator')
            ->assertSee('Bola Naomi Soko')
            ->assertSee('Get the prompts')
            ->assertSee('Show me the prompts')
            ->assertDontSee('SECRET-PROMPT-BODY');
    }

    public function test_a_session_without_prompts_shows_nothing(): void
    {
        PanelSession::create([
            'title' => 'P5', 'slug' => 'panel-5', 'tagline' => 'Panel 5', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'past', 'sort_order' => 5,
        ]);

        $this->get('/sessions/panel-5')
            ->assertOk()
            ->assertDontSee('The prompts from the live builds')
            ->assertDontSee('Get the prompts');
    }

    public function test_unlocking_shows_the_prompts_and_remembers_the_visitor(): void
    {
        $panel = $this->panelWithPrompts();

        $response = $this->post(route('sessions.prompts.unlock', $panel), [
            'name' => 'Rae Smith', 'email' => 'Rae@Example.com',
        ]);

        $response->assertRedirect(route('sessions.show', $panel).'#prompts');
        $response->assertCookie('sc_prompts');

        $this->followRedirects($response)
            ->assertSee('SECRET-PROMPT-BODY')
            ->assertSee('Copy');

        // A later visit with the remembered cookie skips the form.
        $this->withCookie('sc_prompts', json_encode([$panel->id]))
            ->get('/sessions/founders-build-live')
            ->assertSee('SECRET-PROMPT-BODY')
            ->assertDontSee('Show me the prompts');

        $unlock = PromptUnlock::sole();
        $this->assertSame('rae@example.com', $unlock->email);
        $this->assertFalse($unlock->opted_in);

        // Not ticked: not added to the list or EmailOctopus.
        $this->assertSame(0, SessionRegistration::count());
        $this->assertSame([], $this->subscribed);
    }

    public function test_ticking_the_box_joins_the_contacts_bank_once(): void
    {
        $panel = $this->panelWithPrompts();

        foreach ([1, 2] as $_) {
            $this->post(route('sessions.prompts.unlock', $panel), [
                'name' => 'Rae Smith', 'email' => 'rae@example.com', 'keep_posted' => '1',
            ]);
        }

        $this->assertSame(1, PromptUnlock::count());
        $this->assertTrue(PromptUnlock::sole()->opted_in);
        $this->assertSame(1, SessionRegistration::count());
        $this->assertStringContainsString('Asked for the prompts', SessionRegistration::sole()->notes);
        $this->assertNotNull(SessionRegistration::sole()->consented_at);
        $this->assertSame(['sessions', 'prompts'], $this->subscribed[0][1]);

        // A later unlock without the tick does not quietly withdraw them.
        $this->post(route('sessions.prompts.unlock', $panel), ['name' => 'Rae Smith', 'email' => 'rae@example.com']);
        $this->assertTrue(PromptUnlock::sole()->opted_in);
    }

    public function test_the_unlock_form_validates_and_traps_bots(): void
    {
        $panel = $this->panelWithPrompts();

        $this->post(route('sessions.prompts.unlock', $panel), ['name' => '', 'email' => 'not-an-email'])
            ->assertSessionHasErrors(['name', 'email']);

        $this->post(route('sessions.prompts.unlock', $panel), [
            'name' => 'Bot', 'email' => 'bot@example.com', 'pr_reference' => 'filled',
        ])->assertRedirect();

        $this->assertSame(0, PromptUnlock::count());
    }

    public function test_admins_add_edit_and_remove_prompts(): void
    {
        $panel = $this->panelWithPrompts();
        $speaker = $panel->speakers()->first();
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('admin.panels.edit', $panel))
            ->assertOk()
            ->assertSee('Prompts from the live builds')
            ->assertSee('Build the certificate generator');

        $this->post(route('admin.prompts.store', $panel), [
            'title' => 'Step 2: deploy it', 'body' => 'Deploy the app.', 'panel_speaker_id' => $speaker->id,
        ])->assertRedirect();

        $added = SessionPrompt::where('title', 'Step 2: deploy it')->sole();
        $this->assertSame(2, $added->sort_order);

        $this->patch(route('admin.prompts.update', $added), [
            'title' => 'Step 2: ship it', 'body' => 'Ship the app.', 'panel_speaker_id' => '',
        ])->assertRedirect();
        $this->assertSame('Step 2: ship it', $added->fresh()->title);
        $this->assertNull($added->fresh()->panel_speaker_id);

        // A speaker from another session cannot be attached.
        $stranger = PanelSpeaker::create(['name' => 'Someone Else', 'title' => 'T', 'bio' => 'B.']);
        $this->patch(route('admin.prompts.update', $added), [
            'title' => 'X', 'body' => 'Y', 'panel_speaker_id' => $stranger->id,
        ])->assertSessionHasErrorsIn('prompt'.$added->id, ['panel_speaker_id']);

        $this->delete(route('admin.prompts.destroy', $added))->assertRedirect();
        $this->assertSame(1, SessionPrompt::count());
    }

    public function test_admin_sees_who_unlocked_the_prompts(): void
    {
        $panel = $this->panelWithPrompts();
        $this->post(route('sessions.prompts.unlock', $panel), ['name' => 'Rae Smith', 'email' => 'rae@example.com', 'keep_posted' => '1']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.panels.edit', $panel))
            ->assertSee('1 person has unlocked the prompts, 1 joined the mailing list')
            ->assertSee('rae@example.com');
    }

    public function test_the_recap_points_to_the_prompts_when_there_are_some(): void
    {
        $panel = $this->panelWithPrompts();
        $panel->update(['recording_url' => 'https://streamyard.com/e/aw3455fycpkx']);

        $html = (new PanelRecap($panel, 'Rae'))->build()->render();
        $this->assertStringContainsString('https://skillscoop.org/sessions/founders-build-live#prompts', $html);

        $panel->prompts()->delete();
        $html = (new PanelRecap($panel->fresh(), 'Rae'))->build()->render();
        $this->assertStringNotContainsString('#prompts', $html);
    }
}
