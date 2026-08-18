<?php

namespace Database\Seeders;

use App\Models\PanelMedia;
use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

class Panel3Seeder extends Seeder
{
    public function run(): void
    {
        // ── Mark Panel 2 as past, in case this runs standalone ──────────────
        PanelSession::where('slug', 'panel-2-ai-public-services-and-the-people-left-out')
            ->where('status', '!=', 'past')
            ->update(['status' => 'past']);

        // Panel 3 was scaffolded as a "coming soon" placeholder under a
        // temporary slug. Rename that row rather than creating a second one,
        // so its id survives and nothing attached to it is orphaned.
        PanelSession::where('slug', 'panel-3-coming-soon')
            ->update(['slug' => 'panel-3-the-data-skills-gap']);

        // ── Panel Session 3 (past: took place 18 August 2026) ────────────────
        $panel3Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 3',
            'tagline'         => 'The Data Skills Gap',
            'description'     => <<<'TXT'
                Every employer says they cannot find data talent. Every training provider says they are producing it. Both statements are true, and that is the problem.

                This session brings together practitioners across data science, AI education, and workforce development to interrogate the harder questions. What do employers actually mean when they say they need data skills, versus what they put in a job description? Who is training the talent that is supposed to close this gap, and are they training for the jobs that exist or the jobs that existed five years ago? Who can access these training pathways in the first place, and who is structurally locked out before they even apply?
                TXT,
            'event_date'      => '2026-08-18 18:30:00',
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => 'https://www.youtube.com/live/BC5SHm9TCVk',
            'status'          => 'past',
            'sort_order'      => 3,
        ];

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-3-the-data-skills-gap'],
            $panel3Attributes
        );

        // ── Speakers ─────────────────────────────────────────────────────────
        // Ordered demand → supply → assurance → infrastructure: who buys the
        // skills, who trains them, who has to trust the output, who builds the
        // platform. Bios are the speakers' own copy from their intro cards.
        //
        // Note: "Bola Soko" is a different person from "Dr Bola John FRSA" on
        // Panel 2. updateOrCreate keys on name, so the two stay separate rows.
        $speakers = [
            [
                'name'         => 'Bola Soko',
                'title'        => 'Procurement Leader & Founder',
                'company'      => 'Women in AI & Automation',
                'bio'          => 'Bola Soko brings the buyer\'s view of the data skills gap. A procurement leader with over two decades of experience, from the London 2012 Olympic build to the Dept of Business (BEIS), she is now building AI agents herself and founded Women in AI & Automation.',
                'photo_path'   => 'images/speakers/bola-soko.jpg',
                'linkedin_url' => null,
                'topic'        => 'The buyer\'s view of the data skills gap',
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Dr Celestine Achi',
                'title'        => 'Founder',
                'company'      => 'Cihan Digital Academy',
                'bio'          => 'Dr Celestine Achi will bring the training provider\'s perspective to the data skills gap. Founder of Cihan Digital Academy, he has trained over 5,600 professionals across Africa and is on a mission to reach 100,000 with practical AI literacy, connecting learning directly to workplace application and employability.',
                'photo_path'   => 'images/speakers/celestine-achi.jpg',
                'linkedin_url' => null,
                'topic'        => 'The training provider\'s perspective on practical AI literacy',
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Tobiloba Grace Lawalson',
                'title'        => 'Finance and Risk Professional',
                'company'      => null,
                'bio'          => 'Grace Lawalson brings a finance and risk lens to the data skills conversation. Over six years managing risk for investment portfolios worth over £600 million, holding both a finance qualification and a law degree, she has led projects reaching over 11,000 children across Nigeria.',
                'photo_path'   => 'images/speakers/grace-lawalson.jpg',
                'linkedin_url' => null,
                'topic'        => 'A finance and risk lens on data skills',
                'sort_order'   => 3,
            ],
            [
                'name'         => 'Joshua Adeleke',
                'title'        => 'Data Engineering Leader',
                'company'      => null,
                'bio'          => 'Joshua brings the infrastructure and engineering perspective to modern data and AI. A data engineering leader with years of experience designing scalable data platforms and AI-ready architectures across various sectors, he is passionate about cloud-native data engineering, platform modernisation, and building reusable data frameworks that enable trusted analytics across large organisations.',
                'photo_path'   => 'images/speakers/joshua-adeleke.jpg',
                'linkedin_url' => null,
                'topic'        => 'Infrastructure and engineering for modern data and AI',
                'sort_order'   => 4,
            ],
        ];

        // Build the sync payload and let sync() detach anyone no longer listed,
        // so the DB always matches the lineup that actually appeared.
        $syncData = [];
        foreach ($speakers as $data) {
            $topic      = $data['topic'];
            $sort_order = $data['sort_order'];
            unset($data['topic'], $data['sort_order']);

            $speaker = PanelSpeaker::updateOrCreate(
                ['name' => $data['name']],
                $data
            );

            $syncData[$speaker->id] = [
                'topic'      => $topic,
                'sort_order' => $sort_order,
            ];
        }
        $session->speakers()->sync($syncData);

        // ── Attach recording video for past-sessions archive ─────────────────
        PanelMedia::updateOrCreate(
            [
                'panel_session_id' => $session->id,
                'type'             => 'video',
                'url'              => 'https://www.youtube.com/live/BC5SHm9TCVk',
            ],
            [
                'caption'    => 'Full recording: The Data Skills Gap',
                'sort_order' => 1,
            ]
        );

        // Clean up the original scaffold slug if it is still lying around.
        PanelSession::where('slug', 'panel-3-TODO-slug')->delete();

        $this->command->info('Panel 3 seeded: ' . $session->title . ' with ' . count($speakers) . ' speakers. Marked as past with YouTube recording.');
    }
}
