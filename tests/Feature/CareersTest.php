<?php

namespace Tests\Feature;

use App\Models\VolunteerRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Paid vacancies, and keeping them out of the volunteer pipeline.
 *
 * The organisation is hiring its first employee and there was nowhere to put
 * the post. The risk in bolting it onto volunteer_roles is that the two kinds
 * of role leak into each other: a jobseeker filling in a volunteer form that
 * asks for availability and takes no CV would have their application quietly
 * lost, which is most of what these tests are about.
 */
class CareersTest extends TestCase
{
    use RefreshDatabase;

    private function paidRole(array $overrides = []): VolunteerRole
    {
        return VolunteerRole::create(array_merge([
            'title'            => 'Executive Assistant & Content Lead',
            'slug'             => 'executive-assistant-content-lead',
            'engagement_type'  => 'employee',
            'summary'          => 'Our first paid hire.',
            'description'      => 'A generalist role working directly with the Founder.',
            'employment_basis' => 'Full-time',
            'location'         => 'Remote first, UK-adjacent time zones',
            'reports_to'       => 'Founder',
            'apply_email'      => 'hr@skillscoop.org',
            'apply_instructions' => 'Send your CV and a short portfolio.',
            'grants_access'    => 'volunteer',
            'is_open'          => true,
            'sections'         => [
                ['heading' => 'What you will own', 'items' => ['Calendar and inbox', 'Social media end to end']],
            ],
        ], $overrides));
    }

    private function volunteerRole(array $overrides = []): VolunteerRole
    {
        return VolunteerRole::create(array_merge([
            'title'           => 'Volunteer Project Manager',
            'slug'            => 'volunteer-project-manager',
            'engagement_type' => 'volunteer',
            'summary'         => 'Delivery planning across the programme.',
            'grants_access'   => 'volunteer',
            'is_open'         => true,
        ], $overrides));
    }

    public function test_the_listing_shows_open_paid_roles(): void
    {
        $this->paidRole();

        $this->get('/careers')
            ->assertOk()
            ->assertSee('Executive Assistant &amp; Content Lead', false)
            ->assertSee('Full-time');
    }

    public function test_the_listing_leaves_out_volunteer_roles(): void
    {
        $this->volunteerRole();

        $this->get('/careers')
            ->assertOk()
            ->assertDontSee('Volunteer Project Manager')
            ->assertSee('No roles are open at the moment.');
    }

    public function test_a_vacancy_page_carries_the_description_and_how_to_apply(): void
    {
        $this->paidRole();

        $this->get('/careers/executive-assistant-content-lead')
            ->assertOk()
            ->assertSee('What you will own')
            ->assertSee('Social media end to end')
            ->assertSee('Reports to Founder')
            ->assertSee('hr@skillscoop.org');
    }

    /**
     * A volunteer role has no salary, no closing date and no inbox to apply to,
     * so rendering one here would produce a job page with every employment fact
     * missing.
     */
    public function test_a_volunteer_role_is_not_reachable_as_a_vacancy(): void
    {
        $this->volunteerRole();

        $this->get('/careers/volunteer-project-manager')->assertNotFound();
    }

    /**
     * The number in the source document had no currency and no period, so the
     * field is left unset. An empty "Salary:" row invites the reader to assume
     * the worst, so the page omits it entirely rather than showing it blank.
     */
    public function test_no_salary_is_shown_when_none_is_set(): void
    {
        $this->paidRole(['compensation' => null]);

        $this->get('/careers/executive-assistant-content-lead')
            ->assertOk()
            ->assertDontSee('Salary');

        $this->paidRole(['slug' => 'other', 'title' => 'Other', 'compensation' => '£32,000 per year']);

        $this->get('/careers/other')->assertOk()->assertSee('£32,000 per year');
    }

    /** The listing is the one place the vacancy has to be findable from. */
    public function test_a_closed_vacancy_leaves_the_listing_but_keeps_its_page(): void
    {
        $this->paidRole(['is_open' => false]);

        $this->get('/careers')->assertOk()->assertDontSee('Executive Assistant');

        // Still resolves: the link is in inboxes and on job boards by now, and
        // "this has closed" beats a dead end.
        $this->get('/careers/executive-assistant-content-lead')
            ->assertOk()
            ->assertSee('This role is closed.')
            ->assertSee('noindex', false);
    }

    public function test_a_closing_date_takes_the_vacancy_down_by_itself(): void
    {
        $this->paidRole(['closes_at' => now()->subDay()->toDateString()]);

        $this->get('/careers')->assertOk()->assertDontSee('Executive Assistant');
        $this->get('/careers/executive-assistant-content-lead')->assertOk()->assertSee('This role is closed.');
    }

    /**
     * The important one. The volunteer form asks for availability and takes no
     * CV, so a paid post appearing in it would route a jobseeker into the
     * volunteer pipeline.
     */
    public function test_paid_roles_do_not_appear_on_the_volunteer_form(): void
    {
        $this->paidRole();
        $this->volunteerRole();

        $this->get('/volunteer/apply')
            ->assertOk()
            ->assertSee('Volunteer Project Manager')
            ->assertDontSee('Executive Assistant');
    }

    /** Filtering the dropdown decides what is offered; this decides what is accepted. */
    public function test_the_volunteer_form_rejects_a_paid_role_id(): void
    {
        $paid = $this->paidRole();

        $this->post('/volunteer/apply', [
            'volunteer_role_id' => $paid->id,
            'name'              => 'Ada Applicant',
            'email'             => 'ada@example.com',
            'about'             => 'I would like to help.',
            'availability'      => 'Two hours a week',
            'consent'           => '1',
        ])->assertSessionHasErrors('volunteer_role_id');

        $this->assertDatabaseCount('volunteer_engagements', 0);
    }

    /**
     * Google Jobs and most aggregators build their listings from this markup,
     * and free reach is the whole recruitment budget.
     */
    public function test_the_vacancy_publishes_job_posting_structured_data(): void
    {
        $this->paidRole();

        $html = $this->get('/careers/executive-assistant-content-lead')->assertOk()->getContent();

        $this->assertStringContainsString('"@type":"JobPosting"', $html);
        $this->assertStringContainsString('"employmentType":"FULL_TIME"', $html);
        // Remote is declared properly rather than by inventing an office
        // address nobody works at.
        $this->assertStringContainsString('"jobLocationType":"TELECOMMUTE"', $html);
        $this->assertStringNotContainsString('"baseSalary"', $html);
    }

    public function test_the_vacancy_is_in_the_sitemap_and_drops_out_when_closed(): void
    {
        $role = $this->paidRole();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/careers/executive-assistant-content-lead');

        $role->update(['is_open' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/careers/executive-assistant-content-lead');
    }
}
