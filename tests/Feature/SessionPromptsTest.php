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

    public function test_the_founders_prompts_seed_in_full_and_rerun_cleanly(): void
    {
        $this->seed(\Database\Seeders\Panel4Seeder::class);
        $this->seed(\Database\Seeders\Panel4SplitSeeder::class);
        $this->seed(\Database\Seeders\Panel4LineupSeeder::class);
        $this->seed(\Database\Seeders\FoundersPromptsSeeder::class);
        $this->seed(\Database\Seeders\FoundersPromptsSeeder::class);

        $panel = PanelSession::where('slug', 'founders-build-live')->first();
        $prompts = $panel->prompts()->with('speaker')->get();

        $this->assertSame(4, $prompts->count());
        $this->assertSame('Bola Naomi Soko', $prompts[0]->speaker->name);
        $this->assertSame('Adeyinka Prime', $prompts[2]->speaker->name);

        // The long build prompt arrives whole, beginning to end.
        $build = $prompts->firstWhere('sort_order', 21)->body;
        $this->assertGreaterThan(25000, strlen($build));
        $this->assertStringStartsWith('You are an experienced full-stack software engineer', $build);
        $this->assertStringEndsWith('**START IMPLEMENTATION NOW.**', $build);

        // Locked, the page lists the titles; unlocked, it shows the texts.
        $this->get('/sessions/founders-build-live')
            ->assertSee('Certificate Studio: the full build prompt')
            ->assertSee('WorkProof AI: participant guide (start here)')
            ->assertDontSee('VIBE CODING FOUNDERS BUILD LIVE | CERTIFICATE STUDIO');

        $this->withCookie('sc_prompts', json_encode([$panel->id]))
            ->get('/sessions/founders-build-live')
            ->assertSee('VIBE CODING FOUNDERS BUILD LIVE | CERTIFICATE STUDIO')
            ->assertSee('Personal Job Alert Agent');
    }

    public function test_a_long_build_prompt_can_be_saved_from_the_admin(): void
    {
        $panel = $this->panelWithPrompts();
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.prompts.store', $panel), [
            'title' => 'A long build prompt', 'body' => str_repeat('Build it properly. ', 1800),
        ])->assertSessionHasNoErrors();

        $this->assertSame(1, SessionPrompt::where('title', 'A long build prompt')->count());
    }

    private function panel4WithAbisola(): PanelSession
    {
        $this->seed(\Database\Seeders\Panel4Seeder::class);
        $panel = PanelSession::where('slug', 'panel-4')->first();
        $panel->update(['status' => 'past', 'recording_url' => 'https://www.youtube.com/live/41qj4avx-Pg']);

        $abisola = PanelSpeaker::firstOrCreate(['name' => 'Abisola Areola'], ['title' => 'Founder', 'bio' => 'B.']);
        $panel->speakers()->syncWithoutDetaching([$abisola->id => ['sort_order' => 3]]);

        return $panel;
    }

    public function test_abisolas_build_seeds_in_order_with_notes_kept_out_of_the_copy(): void
    {
        $panel = $this->panel4WithAbisola();
        $this->seed(\Database\Seeders\Panel4PromptsSeeder::class);
        $this->seed(\Database\Seeders\Panel4PromptsSeeder::class);

        $prompts = $panel->prompts()->with('speaker')->get();
        $this->assertSame(17, $prompts->count());
        $this->assertSame('Job application assistant: start here', $prompts->first()->title);
        $this->assertSame('A sample advert to practise with', $prompts->last()->title);
        $this->assertTrue($prompts->every(fn ($p) => $p->speaker?->name === 'Abisola Areola'));

        // The recording placeholder is filled and no public "4a" label.
        $all = $prompts->pluck('body')->implode("\n");
        $this->assertStringContainsString('https://www.youtube.com/live/41qj4avx-Pg', $all);
        $this->assertStringNotContainsString('RECORDING LINK', $all);
        $this->assertStringNotContainsString('Panel 4a', $all);

        $html = $this->withCookie('sc_prompts', json_encode([$panel->id]))
            ->get('/sessions/panel-4')->assertOk()->getContent();

        // Notes show, but never inside the block the Copy button copies.
        $this->assertStringContainsString('Real use: answer the questions at the end yourself', $html);
        preg_match_all('#<pre[^>]*class="ss-prompt-body"[^>]*>(.*?)</pre>#s', $html, $blocks);
        $this->assertCount(17, $blocks[1]);
        foreach ($blocks[1] as $block) {
            $this->assertStringNotContainsString('Tag every changed line [made-up]', $block);
            $this->assertStringNotContainsString('Real use: answer the questions', $block);
            $this->assertStringNotContainsString('caught a real slip', $block);
        }
    }

    public function test_the_prompts_email_reaches_the_bank_once_with_both_sessions(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $founders = $this->panelWithPrompts();
        $panel4 = $this->panel4WithAbisola();
        $this->seed(\Database\Seeders\Panel4PromptsSeeder::class);
        // A Panel 4 speaker whose prompts are not up yet.
        $ore = PanelSpeaker::create(['name' => 'Ore Badmus', 'title' => 'T', 'bio' => 'B.']);
        $panel4->speakers()->attach($ore->id, ['sort_order' => 2]);

        SessionRegistration::create(['name' => 'Rae Smith', 'email' => 'rae@example.com', 'interest_type' => 'curious']);
        SessionRegistration::create(['name' => 'Rae Again', 'email' => 'RAE@example.com', 'interest_type' => 'attendee']);
        SessionRegistration::create(['name' => 'Janet Blair', 'email' => 'janet@example.com', 'interest_type' => 'curious']);

        $this->artisan('panels:prompts-email', ['slugs' => ['panel-4', 'founders-build-live'], '--dry-run' => true])
            ->expectsOutputToContain('2 would be emailed.')
            ->assertSuccessful();
        \Illuminate\Support\Facades\Mail::assertNothingSent();

        $this->artisan('panels:prompts-email', ['slugs' => ['panel-4', 'founders-build-live']])
            ->expectsOutputToContain('Sent 2 emails.')
            ->assertSuccessful();
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\PromptsAvailable::class, 2);

        // A rerun reaches nobody twice; a newly banked contact gets it alone.
        SessionRegistration::create(['name' => 'New Person', 'email' => 'new@example.com', 'interest_type' => 'curious']);
        $this->artisan('panels:prompts-email', ['slugs' => ['panel-4', 'founders-build-live']])
            ->expectsOutputToContain('Sent 1 email.')
            ->assertSuccessful();
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\PromptsAvailable::class, 3);

        $mail = new \App\Mail\PromptsAvailable(collect([$panel4->fresh('speakers'), $founders->fresh('speakers')]), 'Rae Smith');
        $built = $mail->build();
        $this->assertTrue($built->hasReplyTo(config('organisation.email')));
        $html = $built->render();

        $this->assertStringContainsString('Hi Rae,', $html);
        $this->assertStringContainsString('https://skillscoop.org/sessions/panel-4#prompts', $html);
        $this->assertStringContainsString('https://skillscoop.org/sessions/founders-build-live#prompts', $html);
        $this->assertStringContainsString('Prompts from Abisola Areola.', $html);
        $this->assertStringContainsString('Prompts from Bola Naomi Soko.', $html);
        // Panel 4 has speakers whose prompts are not up yet.
        $this->assertStringContainsString('More prompts from the other speakers are on the way', $html);
        $this->assertStringNotContainsString('localhost', $html);
        // House style, in what readers see: the shared layout's comments and
        // style block aside.
        $this->assertStringNotContainsString('—', preg_replace(['/<!--.*?-->/s', '#<style.*?</style>#s'], '', $html));
    }

    public function test_the_prompts_email_refuses_a_session_with_no_prompts(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $this->panel4WithAbisola();
        SessionRegistration::create(['name' => 'Rae Smith', 'email' => 'rae@example.com', 'interest_type' => 'curious']);

        $this->artisan('panels:prompts-email', ['slugs' => ['panel-4']])->assertFailed();
        $this->artisan('panels:prompts-email', ['slugs' => ['no-such-panel']])->assertFailed();
        \Illuminate\Support\Facades\Mail::assertNothingSent();
    }
}
