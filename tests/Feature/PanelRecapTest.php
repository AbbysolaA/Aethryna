<?php

namespace Tests\Feature;

use App\Mail\PanelRecap;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Closing out a panel: the recording goes up, the bank gets the recap
 * once, and contacts from the EmailOctopus era can be imported without
 * duplicating anyone.
 */
class PanelRecapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->travelTo('2026-10-22 12:00:00');
    }

    private function finishedPanel(): PanelSession
    {
        return PanelSession::create([
            'title'         => 'The Skills Co-op Sessions: Panel 4',
            'slug'          => 'panel-4',
            'tagline'       => 'Vibe Coding: Live Builds',
            'description'   => 'Real builds.',
            'event_date'    => '2026-10-20 18:30:00',
            'status'        => 'past',
            'sort_order'    => 4,
            'recording_url' => 'https://www.youtube.com/live/41qj4avx-Pg',
        ]);
    }

    public function test_the_completion_seeder_embeds_the_recording_and_hands_over_the_index(): void
    {
        $this->seed(\Database\Seeders\Panel4Seeder::class);
        $this->seed(\Database\Seeders\Panel4SplitSeeder::class);
        $this->seed(\Database\Seeders\Panel4CompleteSeeder::class);

        $panel = PanelSession::where('slug', 'panel-4')->firstOrFail();
        $this->assertSame('past', $panel->status);
        $this->assertSame('https://www.youtube.com/live/41qj4avx-Pg', $panel->recording_url);

        // The archive page embeds the recording and takes no registrations.
        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('youtube-nocookie.com/embed/41qj4avx-Pg', false)
            ->assertDontSee('register-section');

        // The founders session is now the featured panel with the form.
        $this->get('/sessions')
            ->assertOk()
            ->assertSee('Vibe Coding: Founders Build Live')
            ->assertSee('register-section');
    }

    public function test_the_emailoctopus_import_banks_contacts_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // One already known, one new, one junk.
        SessionRegistration::create([
            'name' => 'Bola John', 'email' => 'bolanlejohne@gmail.com', 'interest_type' => 'curious',
        ]);

        $csv = "Identifier,\"Email address\",\"First name\",\"Last name\",Tags,Created,\"Last changed\"\n"
            ."a,janblair97@yahoo.com,JANET,BLAIR,,2026-08-18T08:48:31+00:00,2026-08-18T08:48:31+00:00\n"
            ."b,bolanlejohne@gmail.com,Bola,John,,2026-08-18T19:23:23+00:00,2026-08-18T19:23:23+00:00\n"
            ."c,not-an-email,X,Y,,2026-08-18T19:23:23+00:00,2026-08-18T19:23:23+00:00\n";

        $this->actingAs($admin)
            ->post(route('admin.registrations.import'), [
                'contacts' => UploadedFile::fake()->createWithContent('export.csv', $csv),
            ])
            ->assertSessionHas('status', fn ($m) => str_contains($m, '1 contact banked'));

        $janet = SessionRegistration::where('email', 'janblair97@yahoo.com')->firstOrFail();
        $this->assertSame('JANET BLAIR', $janet->name);
        $this->assertSame('2026-08-18', $janet->created_at->toDateString());
        $this->assertSame(1, SessionRegistration::where('email', 'bolanlejohne@gmail.com')->count());
        $this->assertSame(2, SessionRegistration::count());

        // Re-importing the same file banks nobody twice.
        $this->actingAs($admin)->post(route('admin.registrations.import'), [
            'contacts' => UploadedFile::fake()->createWithContent('export.csv', $csv),
        ]);
        $this->assertSame(2, SessionRegistration::count());
    }

    public function test_the_recap_goes_to_the_bank_once_per_address(): void
    {
        $panel = $this->finishedPanel();
        PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live', 'tagline' => 'Vibe Coding: Founders Build Live',
            'description' => 'D', 'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        SessionRegistration::create(['name' => 'Rae', 'email' => 'rae@example.com', 'interest_type' => 'attendee', 'panel_session_id' => $panel->id]);
        SessionRegistration::create(['name' => 'Rae Again', 'email' => 'RAE@example.com', 'interest_type' => 'curious']);
        SessionRegistration::create(['name' => 'Janet', 'email' => 'janet@example.com', 'interest_type' => 'curious']);

        $this->artisan('panels:recap', ['slug' => 'panel-4'])->assertSuccessful();

        Mail::assertSent(PanelRecap::class, 2);
        Mail::assertSent(PanelRecap::class, fn ($m) => $m->hasTo('janet@example.com'));
        $this->assertSame(0, SessionRegistration::whereNull('recap_sent_at')->count());

        // Running again reaches nobody twice.
        $this->artisan('panels:recap', ['slug' => 'panel-4'])->assertSuccessful();
        Mail::assertSent(PanelRecap::class, 2);

        // A contact banked later gets it on the next run, alone.
        SessionRegistration::create(['name' => 'New Person', 'email' => 'new@example.com', 'interest_type' => 'curious']);
        $this->artisan('panels:recap', ['slug' => 'panel-4'])->assertSuccessful();
        Mail::assertSent(PanelRecap::class, 3);
    }

    public function test_the_recap_email_carries_the_essentials(): void
    {
        $panel = $this->finishedPanel();
        $next = PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live', 'tagline' => 'Vibe Coding: Founders Build Live',
            'description' => 'D', 'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        $built = (new PanelRecap($panel, 'Janet Blair', $next))->build();

        $this->assertSame('Watch it back: Vibe Coding: Live Builds', $built->subject);
        $this->assertTrue($built->hasReplyTo(config('organisation.email')));

        $html = $built->render();
        $this->assertStringContainsString('youtube.com/live/41qj4avx-Pg', $html);
        $this->assertStringContainsString('Subscribe to the channel', $html);
        $this->assertStringContainsString('youtube.com/@TheSkillsCoOpSessions', $html);
        $this->assertStringContainsString('Vibe Coding: Founders Build Live', $html);
        $this->assertStringContainsString('rather not hear about future sessions', $html);
    }

    public function test_the_recap_refuses_a_panel_without_a_recording(): void
    {
        PanelSession::create([
            'title' => 'P5', 'slug' => 'panel-5', 'tagline' => 'Panel 5', 'description' => 'D',
            'event_date' => '2026-09-01 18:30:00', 'status' => 'past', 'sort_order' => 5,
        ]);
        SessionRegistration::create(['name' => 'Rae', 'email' => 'rae@example.com', 'interest_type' => 'curious']);

        $this->artisan('panels:recap', ['slug' => 'panel-5'])->assertFailed();
        Mail::assertNothingSent();
    }
}
