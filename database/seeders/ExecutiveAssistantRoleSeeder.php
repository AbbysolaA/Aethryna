<?php

namespace Database\Seeders;

use App\Models\VolunteerRole;
use Illuminate\Database\Seeder;

/**
 * Executive Assistant & Content Lead.
 *
 * Follows the job description document, so the page and the file say the same
 * thing. Two phrasings differ on purpose, both noted below: the summary, and
 * the salary.
 *
 * updateOrCreate on the slug: re-running corrects the copy rather than creating
 * a second vacancy, and the salary can be filled in by re-seeding once it is
 * settled without touching a row by hand.
 */
class ExecutiveAssistantRoleSeeder extends Seeder
{
    public function run(): void
    {
        VolunteerRole::updateOrCreate(
            ['slug' => 'executive-assistant-content-lead'],
            [
                'title'           => 'Executive Assistant & Content Lead',
                'engagement_type' => 'employee',

                // Describes the work rather than the hiring milestone. "Our
                // first paid hire" says more about the organisation than the
                // job, and it implies everyone else is working for nothing.
                'summary' => 'A broad role across operations and content, working closely with the Founder.',

                'description' => 'Skills Co-op is run as a remote-first organisation. This is a generalist role for someone who can genuinely handle both ends of the job: running the operations side, and owning our social media and content output. You will work directly with the Founder on everything. As we grow and secure further funding, this role will split into specialist positions, and early hires get first consideration for those.',

                'employment_basis' => 'Full-time',
                'location'         => 'Remote first, open to applicants in UK-adjacent time zones (GMT to GMT+2)',
                'reports_to'       => 'Founder',

                /*
                 * Deliberately not set from the document.
                 *
                 * The JD reads "Salary 100,000" with no currency and no period.
                 * For this role at an early-stage CIC that sits far enough
                 * outside the UK market to read as a placeholder rather than
                 * an offer, and publishing it would
                 * shape the applicant pool around a number that may be wrong.
                 * The page omits the row entirely while this is null, which is
                 * a smaller problem than advertising the wrong figure.
                 */
                'compensation' => null,

                'apply_email' => 'hr@skillscoop.org',
                'apply_instructions' => 'Send your CV and a short portfolio or examples of content you have created, with a subject line telling us why this role fits you.',

                'sections' => [
                    [
                        // One list rather than two sublabelled ones. Splitting
                        // it made the role look like two jobs bolted together,
                        // which is the impression a generalist post least needs
                        // to give.
                        'heading' => 'What you will own',
                        'items'   => [
                            'Manage the Founder\'s calendar and inbox, scheduling calls and meetings across speakers, partners, and advisers',
                            'Triage incoming email and draft responses to routine enquiries',
                            'Prepare documents, letters, and briefing packs',
                            'Keep task tracking tools current and flag what is falling behind',
                            'Provide general operational support wherever it is needed that week',
                            'Own Instagram, TikTok, Facebook, YouTube and LinkedIn end to end: content calendar, posting, and community response',
                            'Write and post content, including short-form video, without waiting to be told what to make',
                            'Design graphics and visual assets on brand',
                            'Turn events, wins, and announcements into content the same week they happen',
                            'Track what is performing and adjust without needing to be asked',
                        ],
                    ],
                    [
                        'heading' => 'What you need',
                        'items'   => [
                            'Strong organisational skills and genuine reliability with someone else\'s schedule',
                            'Native fluency in social platforms, not just as a user, but as someone who understands what makes content work',
                            'Comfortable designing graphics in Canva or similar, without needing a designer on standby',
                            'Confident writing in a clear, professional voice, and willing to write and post frequently',
                            'Able to work with real-time overlap during UK working hours, given the calendar and admin side of this role',
                            'Genuinely happy to wear multiple hats and switch between admin and creative work in the same day',
                        ],
                    ],
                    [
                        'heading' => 'Nice to have',
                        'items'   => [
                            'Experience supporting a founder, executive, or small team directly',
                            'Experience growing a brand or organisation\'s social presence from a small base',
                            'Interest in the social impact, education, or skills training space',
                        ],
                    ],
                    [
                        'heading' => 'What you will get',
                        'items'   => [
                            'A genuine seat at the table at a growing organisation, working directly with the Founder',
                            'Full ownership of a real function, not busywork',
                            'First consideration for a specialist role as the organisation scales and roles split out',
                            'Fully remote, flexible working',
                        ],
                    ],
                ],

                /*
                 * A paid post is not a route into the learner-facing area. The
                 * access an employee needs is decided when they start, not
                 * granted by the vacancy record.
                 */
                'grants_access' => 'volunteer',
                'requires_dbs'  => false,
                'requires_nda'  => true,

                // No closing date. The organisation will recruit until it
                // finds the right person rather than to a deadline.
                'closes_at' => null,

                'is_open' => true,
            ]
        );
    }
}
