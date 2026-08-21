<?php

namespace Tests\Feature;

use App\Mail\JobApplicationConfirmation;
use App\Mail\JobApplicationReceived;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\VolunteerRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Applying for a paid role on the vacancy page.
 *
 * The things that must hold: the application lands as a record rather than an
 * inbox thread, the applicant gets a confirmation, the CV never becomes
 * reachable without going through the admin route, and a closed vacancy stops
 * taking applications even from a held-open tab.
 */
class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    private VolunteerRole $role;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');

        $this->role = VolunteerRole::create([
            'title'           => 'Executive Assistant & Content Lead',
            'slug'            => 'executive-assistant-content-lead',
            'engagement_type' => 'employee',
            'summary'         => 'A broad role across operations and content.',
            'apply_email'     => 'hr@skillscoop.org',
            'grants_access'   => 'volunteer',
            'is_open'         => true,
        ]);
    }

    private function apply(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/careers/executive-assistant-content-lead/apply', array_merge([
            'name'       => 'Ada Applicant',
            'email'      => 'ada@example.com',
            'cover_note' => 'I have run operations and content for a small charity.',
            'cv'         => UploadedFile::fake()->create('ada-cv.pdf', 200, 'application/pdf'),
            'consent'    => '1',
        ], $overrides));
    }

    public function test_the_vacancy_page_offers_the_form(): void
    {
        $this->get('/careers/executive-assistant-content-lead')
            ->assertOk()
            ->assertSee('Apply for this role')
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="cv"', false)
            // On-site apply is declared to the schema readers too.
            ->assertSee('"directApply":true', false);
    }

    public function test_an_application_lands_with_its_cv_on_the_private_disk(): void
    {
        $this->apply()->assertRedirect('/careers/executive-assistant-content-lead');

        $application = JobApplication::firstOrFail();

        $this->assertSame('Ada Applicant', $application->name);
        $this->assertSame('new', $application->status);
        $this->assertNotNull($application->consented_at);
        $this->assertSame('ada-cv.pdf', $application->cv_original_name);

        Storage::disk('local')->assertExists($application->cv_path);
        $this->assertStringStartsWith('job-cvs/', $application->cv_path);
        $this->assertStringNotContainsString('ada-cv', $application->cv_path);
    }

    public function test_both_emails_go_out(): void
    {
        $this->apply();

        Mail::assertSent(JobApplicationConfirmation::class, fn ($mail) => $mail->hasTo('ada@example.com'));
        Mail::assertSent(JobApplicationReceived::class, fn ($mail) => $mail->hasTo('hr@skillscoop.org'));
    }

    /** A job application without a CV is not one. Unlike the volunteer form. */
    public function test_the_cv_is_required(): void
    {
        $this->apply(['cv' => null])->assertSessionHasErrors('cv');

        $this->assertDatabaseCount('job_applications', 0);
        Mail::assertNothingSent();
    }

    public function test_it_refuses_a_file_that_is_not_a_document(): void
    {
        $this->apply(['cv' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php')])
            ->assertSessionHasErrors('cv');

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_a_closed_vacancy_refuses_even_a_direct_post(): void
    {
        $this->role->update(['is_open' => false]);

        $this->apply()->assertRedirect('/careers/executive-assistant-content-lead');

        $this->assertDatabaseCount('job_applications', 0);
        Mail::assertNothingSent();
    }

    public function test_a_volunteer_role_cannot_be_applied_to_as_a_job(): void
    {
        VolunteerRole::create([
            'title'           => 'Volunteer Project Manager',
            'slug'            => 'volunteer-project-manager',
            'engagement_type' => 'volunteer',
            'summary'         => 'Delivery planning.',
            'grants_access'   => 'volunteer',
            'is_open'         => true,
        ]);

        $this->post('/careers/volunteer-project-manager/apply', [
            'name'       => 'Ada Applicant',
            'email'      => 'ada@example.com',
            'cover_note' => 'Hello.',
            'cv'         => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'consent'    => '1',
        ])->assertNotFound();
    }

    /**
     * Reapplying while still in the running gets the same thanks and no second
     * row, and reveals nothing about who is on file.
     */
    public function test_a_duplicate_application_is_absorbed(): void
    {
        $this->apply();
        $this->apply();

        $this->assertDatabaseCount('job_applications', 1);
    }

    public function test_the_honeypot_swallows_bots(): void
    {
        $this->apply(['jb_reference' => 'https://spam.example'])
            ->assertRedirect('/careers/executive-assistant-content-lead');

        $this->assertDatabaseCount('job_applications', 0);
        Mail::assertNothingSent();
    }

    public function test_the_cv_is_only_reachable_by_an_admin(): void
    {
        $this->apply();
        $application = JobApplication::firstOrFail();

        $url = '/admin/job-applications/'.$application->id.'/cv';

        $this->get($url)->assertRedirect('/login');

        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner)->get($url)->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get($url);

        $response->assertOk();
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('ada-cv.pdf', $response->headers->get('content-disposition'));
    }

    public function test_an_admin_can_move_an_application_through_the_statuses(): void
    {
        $this->apply();
        $application = JobApplication::firstOrFail();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch('/admin/job-applications/'.$application->id, ['status' => 'shortlisted'])
            ->assertRedirect(route('admin.job-applications.index'));

        $this->assertSame('shortlisted', $application->fresh()->status);

        $this->actingAs($admin)
            ->patch('/admin/job-applications/'.$application->id, ['status' => 'not-a-status'])
            ->assertSessionHasErrors('status');
    }

    public function test_the_admin_list_renders_with_the_application_on_it(): void
    {
        $this->apply();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/job-applications')
            ->assertOk()
            ->assertSee('Ada Applicant')
            ->assertSee('ada-cv.pdf')
            ->assertSee('I have run operations and content');
    }

    public function test_deleting_an_application_takes_the_cv_with_it(): void
    {
        $this->apply();
        $application = JobApplication::firstOrFail();
        $path = $application->cv_path;

        $application->delete();

        Storage::disk('local')->assertMissing($path);
    }

    /** People who applied must not lose their target from under them. */
    public function test_a_role_with_applications_cannot_be_deleted(): void
    {
        $this->apply();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete('/admin/volunteer-roles/'.$this->role->slug)
            ->assertRedirect(route('admin.volunteer-roles.index'));

        $this->assertDatabaseHas('volunteer_roles', ['id' => $this->role->id]);
    }
}
