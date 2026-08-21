<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerEngagement;
use App\Models\VolunteerRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * CVs attached to volunteer and mentor applications.
 *
 * The form asks for a paragraph and a line on availability, which is enough to
 * decide whether to talk to someone offering a hand at an event and nowhere
 * near enough for a mentor, where the question is what they have actually done.
 *
 * Two things have to hold. The file must never be reachable without going
 * through an authorised route, because it is a stranger's personal document.
 * And it must stay optional, because a required upload turns away the people
 * who have no CV to hand, who are often exactly the people worth talking to.
 */
class VolunteerCvUploadTest extends TestCase
{
    use RefreshDatabase;

    private VolunteerRole $role;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->role = VolunteerRole::create([
            'title'           => 'Volunteer Mentor',
            'slug'            => 'volunteer-mentor',
            'engagement_type' => 'volunteer',
            'summary'         => 'Two hours a month with a learner.',
            'grants_access'   => 'mentor',
            'is_open'         => true,
        ]);
    }

    private function apply(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/volunteer/apply', array_merge([
            'volunteer_role_id' => $this->role->id,
            'name'              => 'Ada Applicant',
            'email'             => 'ada@example.com',
            'about'             => 'I would like to mentor.',
            'availability'      => 'Two hours a month',
            'consent'           => '1',
        ], $overrides));
    }

    public function test_an_application_carries_a_cv_to_a_private_disk(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('ada-cv.pdf', 200, 'application/pdf')])
            ->assertRedirect('/volunteer/apply/thanks');

        $engagement = VolunteerEngagement::firstOrFail();

        $this->assertNotNull($engagement->cv_path);
        $this->assertSame('ada-cv.pdf', $engagement->cv_original_name);
        Storage::disk('local')->assertExists($engagement->cv_path);

        // Not under any path the web server hands out. The stored name is
        // random too, so the URL cannot be guessed from the applicant's name.
        $this->assertStringStartsWith('volunteer-cvs/', $engagement->cv_path);
        $this->assertStringNotContainsString('ada-cv', $engagement->cv_path);
    }

    /**
     * The one that must not regress. A required upload turns away people with
     * no CV to hand, and lived experience is worth as much here as a CV.
     */
    /**
     * The gap a question from the founder exposed: jobs and speaker pitches
     * answered with an email, volunteers only saw a thanks page. Mentors are
     * people we ask to give time for nothing; they should not also be the
     * only applicants we do not write back to.
     */
    public function test_the_applicant_gets_a_confirmation_email(): void
    {
        Storage::fake('local');

        $this->apply();

        \Illuminate\Support\Facades\Mail::assertSent(
            \App\Mail\VolunteerApplicationConfirmation::class,
            fn ($mail) => $mail->hasTo('ada@example.com')
        );
    }

    public function test_an_application_without_a_cv_still_goes_through(): void
    {
        Storage::fake('local');

        $this->apply()->assertRedirect('/volunteer/apply/thanks');

        $engagement = VolunteerEngagement::firstOrFail();

        $this->assertNull($engagement->cv_path);
        $this->assertSame('applied', $engagement->status);
    }

    public function test_it_refuses_a_file_that_is_not_a_document(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php')])
            ->assertSessionHasErrors('cv');

        $this->assertDatabaseCount('volunteer_engagements', 0);
    }

    public function test_it_refuses_a_file_over_the_size_limit(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('huge.pdf', 6000, 'application/pdf')])
            ->assertSessionHasErrors('cv');
    }

    /**
     * A stranger's personal document. Reaching it has to mean going through a
     * route that checks who is asking.
     */
    public function test_a_cv_is_not_downloadable_by_the_public(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('ada-cv.pdf', 100, 'application/pdf')]);
        $engagement = VolunteerEngagement::firstOrFail();

        $this->get('/admin/volunteers/'.$engagement->id.'/cv')->assertRedirect('/login');

        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner)
            ->get('/admin/volunteers/'.$engagement->id.'/cv')
            ->assertForbidden();
    }

    public function test_an_admin_can_download_the_cv(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('ada-cv.pdf', 100, 'application/pdf')]);
        $engagement = VolunteerEngagement::firstOrFail();

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/volunteers/'.$engagement->id.'/cv');

        $response->assertOk();
        // Served as a download under the name the applicant gave it, not the
        // random one on disk.
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('ada-cv.pdf', $response->headers->get('content-disposition'));
    }

    /** The row can outlive the file if storage is cleared underneath it. */
    public function test_a_missing_file_is_a_404_rather_than_a_broken_download(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('ada-cv.pdf', 100, 'application/pdf')]);
        $engagement = VolunteerEngagement::firstOrFail();

        Storage::disk('local')->delete($engagement->cv_path);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin/volunteers/'.$engagement->id.'/cv')->assertNotFound();
    }

    /**
     * Unsolicited personal documents should not outlive the application they
     * came with. Handled on the model's deleting event so a delete added later
     * cannot orphan one.
     */
    public function test_deleting_the_application_takes_the_file_with_it(): void
    {
        Storage::fake('local');

        $this->apply(['cv' => UploadedFile::fake()->create('ada-cv.pdf', 100, 'application/pdf')]);
        $engagement = VolunteerEngagement::firstOrFail();
        $path = $engagement->cv_path;

        Storage::disk('local')->assertExists($path);
        $engagement->delete();
        Storage::disk('local')->assertMissing($path);
    }

    public function test_the_form_can_actually_carry_a_file(): void
    {
        // Without enctype the browser sends the filename only and the upload
        // silently never arrives, which is invisible until someone checks.
        $this->get('/volunteer/apply')
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="cv"', false);
    }
}
