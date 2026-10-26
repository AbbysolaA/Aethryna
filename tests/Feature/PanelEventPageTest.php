<?php

namespace Tests\Feature;

use App\Models\PanelSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The panel page as a full event page: banner, agenda, what to expect, the
 * practical details and the organiser, not just a copy of the index.
 */
class PanelEventPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-10-12 12:00:00');
        Storage::fake('panel_images');
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
            'itinerary'   => ['6.30pm | Doors open and welcome', 'Live builds from the panel'],
            'accessibility_note' => 'Live captions are available throughout.',
        ], $overrides));
    }

    public function test_an_upcoming_panel_reads_as_a_full_event_page(): void
    {
        $this->panel();

        $this->get('/sessions/panel-4')
            ->assertOk()
            // Agenda, with and without a time prefix.
            ->assertSee('How the hour runs')
            ->assertSee('6.30pm')
            ->assertSee('Doors open and welcome')
            ->assertSee('Live builds from the panel')
            // What to expect and the practical details.
            ->assertSee('A panel, not a webinar')
            ->assertSee('The practical bits')
            ->assertSee('The joining link is emailed to registrants before the start')
            ->assertSee('Live captions are available throughout.')
            // The organiser card.
            ->assertSee('Hosted by')
            ->assertSee('community interest company')
            // No speakers yet reads as a promise, not a gap.
            ->assertSee('Speakers announced soon')
            ->assertSee('pitch your talk');
    }

    public function test_a_past_panel_skips_the_event_machinery(): void
    {
        $this->panel(['event_date' => '2026-09-10 18:30:00', 'status' => 'past']);

        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertDontSee('How the hour runs')
            ->assertDontSee('The practical bits')
            ->assertDontSee('Speakers announced soon');
    }

    public function test_an_admin_can_give_a_panel_a_banner(): void
    {
        $panel = $this->panel();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.panels.update', $panel), [
            'title'       => $panel->title,
            'tagline'     => $panel->tagline,
            'description' => $panel->description,
            'event_date'  => '2026-10-20 18:30',
            'status'      => 'upcoming',
            'sort_order'  => 4,
            'banner'      => UploadedFile::fake()->image('flyer.png', 1800, 1000),
        ])->assertSessionHasNoErrors();

        $panel->refresh();

        $this->assertNotNull($panel->banner_path);
        Storage::disk('panel_images')->assertExists($panel->banner_path);

        // Shown on the page and used as the share image.
        $this->get('/sessions/panel-4')
            ->assertOk()
            ->assertSee('images/panels/'.$panel->banner_path, false)
            ->assertSee('property="og:image" content="'.$panel->bannerUrl().'"', false);
    }

    public function test_the_banner_can_be_removed(): void
    {
        $panel = $this->panel(['banner_path' => 'banner-old.jpg']);
        Storage::disk('panel_images')->put('banner-old.jpg', 'x');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.panels.update', $panel), [
            'title'         => $panel->title,
            'tagline'       => $panel->tagline,
            'description'   => $panel->description,
            'status'        => 'upcoming',
            'sort_order'    => 4,
            'banner_remove' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertNull($panel->fresh()->banner_path);
        Storage::disk('panel_images')->assertMissing('banner-old.jpg');
    }

    public function test_the_agenda_can_be_edited_as_plain_lines(): void
    {
        $panel = $this->panel();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.panels.update', $panel), [
            'title'          => $panel->title,
            'tagline'        => $panel->tagline,
            'description'    => $panel->description,
            'status'         => 'upcoming',
            'sort_order'     => 4,
            'itinerary_text' => "6.30pm | Welcome\n\n7pm | Q&A\n",
        ])->assertSessionHasNoErrors();

        $this->assertSame(['6.30pm | Welcome', '7pm | Q&A'], $panel->fresh()->itinerary);
    }
    /**
     * The committed Panel 4 banner is the landscape website version. The
     * first attempt to ship it was lost in a branch reset and the square
     * flyer quietly stayed in master, so the file's shape is now asserted.
     */
    public function test_the_committed_panel_4_banner_is_landscape(): void
    {
        [$width, $height] = getimagesize(public_path('images/panels/panel-4.jpg'));

        $this->assertGreaterThan($height, $width);
    }
    /**
     * Panel 4 is two sessions now, neither labelled A or B: the October
     * live-builds session keeps its slug and registrations under the new
     * name, and the November founders session gets its own page with Ayse
     * listed and nobody unconfirmed published.
     */
    public function test_the_panel_4_split_seeds_both_sessions(): void
    {
        $this->seed(\Database\Seeders\Panel4Seeder::class);
        $this->seed(\Database\Seeders\Panel4SplitSeeder::class);

        $this->assertSame(
            'Vibe Coding: Live Builds',
            \App\Models\PanelSession::where('slug', 'panel-4')->value('tagline')
        );

        $this->get('/sessions/founders-build-live')
            ->assertOk()
            ->assertSee('Vibe Coding: Founders Build Live')
            ->assertSee('10 November 2026')
            ->assertSee('90 minutes')
            ->assertSee('Ayse Basak Cinar')
            ->assertSee('Leadership Consultant and AI Strategist')
            ->assertSee('Adapting a custom GPT into a reusable AI skill, live')
            ->assertSee('More speakers to be announced')
            ->assertSee('Founders build live on screen')
            // 6.30pm GMT for 90 minutes: the schema ends at 8pm with the
            // winter offset, exactly as the listing spec asked.
            ->assertSee('2026-11-10T18:30:00+00:00', false)
            ->assertSee('2026-11-10T20:00:00+00:00', false)
            ->assertDontSee('Adeyinka')
            ->assertDontSee('Bola');

        // Re-running cannot duplicate Ayse or the panel.
        $this->seed(\Database\Seeders\Panel4SplitSeeder::class);
        $this->assertSame(1, \App\Models\PanelSpeaker::where('name', 'Ayse Basak Cinar')->count());
        $this->assertSame(1, \App\Models\PanelSession::where('slug', 'founders-build-live')->count());
    }
    public function test_the_sessions_index_lists_a_second_upcoming_panel(): void
    {
        $this->seed(\Database\Seeders\Panel4Seeder::class);
        $this->seed(\Database\Seeders\Panel4SplitSeeder::class);

        $this->get('/sessions')
            ->assertOk()
            ->assertSee('Also coming up')
            ->assertSee('Vibe Coding: Founders Build Live')
            ->assertSee('/sessions/founders-build-live', false);
    }
    /** Both committed banners are the landscape website versions. */
    public function test_the_committed_founders_banner_is_landscape(): void
    {
        [$width, $height] = getimagesize(public_path('images/panels/founders-build-live.jpg'));

        $this->assertGreaterThan($height, $width);
    }
}
