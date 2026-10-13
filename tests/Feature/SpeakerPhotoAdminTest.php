<?php

namespace Tests\Feature;

use App\Models\PanelSpeaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Speaker photos without SSH: accepting a pitch publishes its headshot, the
 * speakers screen uploads or reuses one, and a speaker who arrived as a
 * pitch links back to their full pitch page.
 */
class SpeakerPhotoAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');
        Storage::fake('speaker_images');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function pitchWithHeadshot(): SpeakerApplication
    {
        $this->post('/apply-to-speak', [
            'name'              => 'Omonigho Okome',
            'email'             => 'omonigho@example.com',
            'organisation'      => 'Rakuten Viber',
            'job_title'         => 'Product Manager',
            'bio'               => 'A decade in product.',
            'talk_title'        => 'Breaking into product from support',
            'talk_summary'      => 'What the move really takes.',
            'consent'           => '1',
            'recording_consent' => '1',
            'headshot'          => UploadedFile::fake()->image('omonigho.jpg', 900, 1200),
        ]);

        return SpeakerApplication::firstOrFail();
    }

    public function test_accepting_a_pitch_publishes_its_headshot(): void
    {
        $application = $this->pitchWithHeadshot();

        $speaker = $application->accept();

        $this->assertNotNull($speaker->photo_path);
        $this->assertStringStartsWith('images/speakers/', $speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));

        // Accepting again does not publish a second copy over it.
        $before = $speaker->photo_path;
        $application->accept();
        $this->assertSame($before, $speaker->fresh()->photo_path);
    }

    public function test_the_speakers_screen_can_reuse_the_pitch_headshot(): void
    {
        $application = $this->pitchWithHeadshot();
        $speaker = PanelSpeaker::create(['name' => 'Omonigho Okome', 'title' => 'Product Manager', 'bio' => 'A decade in product.']);
        $application->forceFill(['panel_speaker_id' => $speaker->id, 'status' => 'accepted'])->save();

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), ['from_pitch' => '1'])
            ->assertSessionHas('status');

        $speaker->refresh();
        $this->assertNotNull($speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));
    }

    public function test_the_speakers_screen_can_upload_a_photo(): void
    {
        $speaker = PanelSpeaker::create(['name' => 'Miriam Fearon', 'title' => 'Customer Success Leader', 'bio' => 'Customer success.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), [
                'photo' => UploadedFile::fake()->image('miriam.png', 1000, 1400),
            ])
            ->assertSessionHas('status');

        $speaker->refresh();
        $this->assertStringStartsWith('images/speakers/', $speaker->photo_path);
        $this->assertStringEndsWith('.jpg', $speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));

        // Replacing deletes the published original.
        $first = basename($speaker->photo_path);
        $this->actingAs($this->admin())->post(route('admin.speakers.photo', $speaker), [
            'photo' => UploadedFile::fake()->image('miriam-two.png', 1000, 1400),
        ]);

        Storage::disk('speaker_images')->assertMissing($first);
        $this->assertNotSame($first, basename($speaker->fresh()->photo_path));
    }

    public function test_an_unreadable_upload_is_refused_politely(): void
    {
        $speaker = PanelSpeaker::create(['name' => 'Sean Adetule', 'title' => 'Partnerships', 'bio' => 'Partnerships.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), [
                'photo' => UploadedFile::fake()->create('not-a-picture.jpg', 60, 'image/jpeg'),
            ])
            ->assertSessionHas('error');

        $this->assertNull($speaker->fresh()->photo_path);
    }

    public function test_speaker_names_link_to_their_pitch_page(): void
    {
        $application = $this->pitchWithHeadshot();
        $speaker = $application->accept();
        PanelSpeaker::create(['name' => 'No Pitch Speaker', 'title' => 'Added by hand', 'bio' => 'Added by hand.']);

        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee(route('admin.speaker-applications.show', $application), false)
            ->assertSee('click for the full page')
            ->assertSee('No Pitch Speaker');
    }
    /**
     * Returning speakers are the series' model: Bola spoke at Panel 3 and
     * joins the founders session too, from her row on the speakers screen.
     */
    public function test_a_speaker_can_be_added_to_another_panel_from_their_row(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $three = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 3', 'slug' => 'panel-3',
            'tagline' => 'Panel 3 · The data skills gap', 'description' => 'D',
            'event_date' => '2026-09-09 18:30:00', 'status' => 'past', 'sort_order' => 3,
        ]);
        $founders = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 4', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        $bola = PanelSpeaker::create(['name' => 'Bola Soko', 'title' => 'Engineer', 'bio' => 'B.']);
        $three->speakers()->attach($bola->id, ['sort_order' => 1]);

        // Her row offers the founders panel and labels the button honestly.
        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee('Panel 3 · The data skills gap')
            ->assertSee('Add to another panel');

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $bola), ['panel_session_id' => $founders->id])
            ->assertSessionHas('status');

        $this->assertTrue($founders->speakers()->whereKey($bola->id)->exists());
        $this->assertTrue($three->speakers()->whereKey($bola->id)->exists());

        // Pressing it twice cannot double her onto the panel.
        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $bola), ['panel_session_id' => $founders->id]);
        $this->assertSame(1, $founders->speakers()->whereKey($bola->id)->count());
    }
}
