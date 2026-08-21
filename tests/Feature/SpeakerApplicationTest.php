<?php

namespace Tests\Feature;

use App\Mail\SpeakerApplicationConfirmation;
use App\Mail\SpeakerApplicationReceived;
use App\Models\PanelSpeaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Pitching a talk at /apply-to-speak.
 *
 * The pitch must land as a record, the confirmation must go out, the headshot
 * must stay private until a person decides otherwise, and accepting must mint
 * the PanelSpeaker the session pages render.
 */
class SpeakerApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');
    }

    private function pitch(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/apply-to-speak', array_merge([
            'name'         => 'Sam Speaker',
            'email'        => 'sam@example.com',
            'organisation' => 'Big Tech Ltd',
            'job_title'    => 'Support Team Lead',
            'bio'          => 'Ten years in tech support, started with no degree.',
            'talk_title'   => 'From the phones to team lead',
            'talk_summary' => 'How a support role becomes a career, and what I would tell myself at the start.',
            'consent'      => '1',
            'recording_consent' => '1',
        ], $overrides));
    }

    public function test_the_page_renders_with_the_form(): void
    {
        $this->get('/apply-to-speak')
            ->assertOk()
            ->assertSee('Pitch your talk')
            ->assertSee('First-time speakers are welcome')
            ->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_a_pitch_lands_and_both_emails_go_out(): void
    {
        $this->pitch()->assertRedirect(route('speakers.apply.thanks'));

        $application = SpeakerApplication::firstOrFail();

        $this->assertSame('From the phones to team lead', $application->talk_title);
        $this->assertSame('new', $application->status);
        $this->assertNotNull($application->consented_at);

        Mail::assertSent(SpeakerApplicationConfirmation::class, fn ($mail) => $mail->hasTo('sam@example.com'));
        Mail::assertSent(SpeakerApplicationReceived::class, fn ($mail) => $mail->hasTo(config('organisation.email')));
    }

    /** No CV, no video, no prior talks: none of that may block a pitch. */
    public function test_the_bare_minimum_pitch_is_enough(): void
    {
        $this->pitch([
            'organisation' => null,
            'job_title'    => null,
        ])->assertRedirect(route('speakers.apply.thanks'));

        $this->assertDatabaseCount('speaker_applications', 1);
    }

    public function test_a_headshot_lands_on_the_private_disk(): void
    {
        $this->pitch(['headshot' => UploadedFile::fake()->create('sam.jpg', 300, 'image/jpeg')]);

        $application = SpeakerApplication::firstOrFail();

        Storage::disk('local')->assertExists($application->headshot_path);
        $this->assertStringStartsWith('speaker-headshots/', $application->headshot_path);

        // And it is not reachable without being an admin.
        $url = '/admin/speaker-applications/'.$application->id.'/headshot';
        $this->get($url)->assertRedirect('/login');

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get($url)->assertOk();
    }

    public function test_it_refuses_a_headshot_that_is_not_an_image(): void
    {
        $this->pitch(['headshot' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf')])
            ->assertSessionHasErrors('headshot');

        $this->assertDatabaseCount('speaker_applications', 0);
    }

    public function test_the_honeypot_swallows_bots(): void
    {
        $this->pitch(['sp_reference' => 'https://spam.example'])
            ->assertRedirect(route('speakers.apply.thanks'));

        $this->assertDatabaseCount('speaker_applications', 0);
        Mail::assertNothingSent();
    }

    public function test_a_second_unread_pitch_from_the_same_address_is_absorbed(): void
    {
        $this->pitch();
        $this->pitch(['talk_title' => 'A different talk']);

        $this->assertDatabaseCount('speaker_applications', 1);
    }

    /**
     * Accepting mints the PanelSpeaker the session pages render, links it
     * back, and does not publish the headshot: photos reach the public folder
     * through speakers:photo, which resizes and strips them.
     */
    public function test_accepting_a_pitch_mints_a_panel_speaker(): void
    {
        $this->pitch();
        $application = SpeakerApplication::firstOrFail();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch('/admin/speaker-applications/'.$application->id, ['status' => 'accepted'])
            ->assertRedirect(route('admin.speaker-applications.index'));

        $application->refresh();

        $this->assertSame('accepted', $application->status);
        $this->assertNotNull($application->panel_speaker_id);

        $speaker = PanelSpeaker::findOrFail($application->panel_speaker_id);
        $this->assertSame('Sam Speaker', $speaker->name);
        $this->assertSame('Support Team Lead', $speaker->title);
        $this->assertSame('Big Tech Ltd', $speaker->company);
        $this->assertNull($speaker->photo_path);
    }

    public function test_accepting_twice_does_not_mint_two_speakers(): void
    {
        $this->pitch();
        $application = SpeakerApplication::firstOrFail();

        $application->accept();
        $first = $application->panel_speaker_id;
        $application->accept();

        $this->assertSame($first, $application->fresh()->panel_speaker_id);
        $this->assertSame(1, PanelSpeaker::count());
    }

    /**
     * The sharp one from the Gatherverse comparison. Sessions are recorded
     * and shared; discovering a booked speaker never agreed to that after the
     * recording exists is a conversation nobody should have.
     */
    public function test_recording_consent_is_required_and_stamped(): void
    {
        $this->pitch(['recording_consent' => null])->assertSessionHasErrors('recording_consent');
        $this->assertDatabaseCount('speaker_applications', 0);

        $this->pitch();
        $this->assertNotNull(SpeakerApplication::firstOrFail()->recording_consented_at);
    }

    public function test_format_preference_and_topics_are_stored(): void
    {
        $this->pitch([
            'session_format' => 'pre-recorded',
            'topic_areas'    => ['Data and AI', 'Routes into tech, any role'],
        ]);

        $application = SpeakerApplication::firstOrFail();

        $this->assertSame('pre-recorded', $application->session_format);
        $this->assertSame('A pre-recorded talk', $application->formatLabel());
        $this->assertSame(['Data and AI', 'Routes into tech, any role'], $application->topic_areas);
    }

    /** No preference is a fine answer and the default. */
    public function test_format_and_topics_stay_optional(): void
    {
        $this->pitch(['session_format' => '']);

        $application = SpeakerApplication::firstOrFail();

        $this->assertNull($application->session_format);
        $this->assertNull($application->topic_areas);
    }

    public function test_an_invented_format_or_topic_is_rejected(): void
    {
        $this->pitch(['session_format' => 'interpretive-dance'])
            ->assertSessionHasErrors('session_format');

        $this->pitch(['topic_areas' => ['Blockchain thought leadership']])
            ->assertSessionHasErrors('topic_areas.0');

        $this->assertDatabaseCount('speaker_applications', 0);
    }

    public function test_the_admin_list_renders_with_the_pitch_on_it(): void
    {
        $this->pitch();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/speaker-applications')
            ->assertOk()
            ->assertSee('Sam Speaker')
            ->assertSee('From the phones to team lead');
    }

    public function test_deleting_a_pitch_takes_the_headshot_with_it(): void
    {
        $this->pitch(['headshot' => UploadedFile::fake()->create('sam.jpg', 300, 'image/jpeg')]);
        $application = SpeakerApplication::firstOrFail();
        $path = $application->headshot_path;

        $application->delete();

        Storage::disk('local')->assertMissing($path);
    }

    public function test_the_speak_page_is_in_the_sitemap(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/apply-to-speak');
    }
}
