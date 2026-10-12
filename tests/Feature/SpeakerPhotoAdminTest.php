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
}
