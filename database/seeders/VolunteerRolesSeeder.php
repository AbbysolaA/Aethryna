<?php

namespace Database\Seeders;

use App\Models\VolunteerRole;
use Illuminate\Database\Seeder;

/**
 * The volunteer roles we currently recruit for.
 *
 * Mentor sits in this list rather than in a pipeline of its own. Accepting it
 * grants 'mentor' access, which is what opens the existing /mentor area, so
 * mentor recruitment and general volunteer recruitment share one flow.
 *
 * Idempotent: run it again after adding a role and existing rows are left
 * alone, so a re-seed cannot wipe engagements pointing at them.
 */
class VolunteerRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'slug'          => 'mentor',
                'title'         => 'Mentor',
                'summary'       => 'One-to-one support for a learner through the 25 weeks.',
                'description'   => 'You are matched with one or two learners and meet them regularly through the programme. You log each session, flag anything that worries you, and help them think through what comes after the cohort. Learner-facing, so a cleared DBS is required before you start.',
                'grants_access' => 'mentor',
                'requires_dbs'  => true,
                'requires_nda'  => true,
            ],
            [
                'slug'          => 'project-manager',
                'title'         => 'Volunteer Project Manager',
                'summary'       => 'Delivery planning and coordination across the programme.',
                'description'   => 'You hold the delivery plan, chase the open items, and keep panels and community sessions moving. Works closely with the founder. Sees learner data, so a DBS check and signed NDA are required.',
                'grants_access' => 'volunteer',
                'requires_dbs'  => true,
                'requires_nda'  => true,
            ],
            [
                'slug'          => 'website-volunteer',
                'title'         => 'Website Volunteer',
                'summary'       => 'Building and maintaining skillscoop.org.',
                'description'   => 'Front-end and content work on the public site. No learner-facing contact and no access to learner records, so no DBS is needed.',
                'grants_access' => 'volunteer',
                'requires_dbs'  => false,
                'requires_nda'  => true,
            ],
            [
                'slug'          => 'panel-facilitator',
                'title'         => 'Panel Facilitator',
                'summary'       => 'Hosting and chairing the monthly panel sessions.',
                'description'   => 'You chair a panel, brief the speakers beforehand, and keep the session to time. Public-facing rather than learner-facing.',
                'grants_access' => 'volunteer',
                'requires_dbs'  => false,
                'requires_nda'  => true,
            ],
            [
                'slug'          => 'community-outreach',
                'title'         => 'Community Outreach Volunteer',
                'summary'       => 'Reaching the people the programme is built for.',
                'description'   => 'You take the programme to partner organisations, community groups and referral routes, and help run discovery sessions. Meets prospective learners in person, so a DBS check is required.',
                'grants_access' => 'volunteer',
                'requires_dbs'  => true,
                'requires_nda'  => true,
            ],
        ];

        foreach ($roles as $role) {
            VolunteerRole::firstOrCreate(
                ['slug' => $role['slug']],
                $role + ['is_open' => true],
            );
        }
    }
}
