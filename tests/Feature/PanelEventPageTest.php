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
}
