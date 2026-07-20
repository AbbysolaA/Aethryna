<?php

namespace Database\Seeders;

use App\Models\PanelMedia;
use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

class Panel2Seeder extends Seeder
{
    public function run(): void
    {
        // ── Mark Panel 1 as past (event took place 16 June 2026) ────────────
        PanelSession::where('slug', 'panel-1-ai-is-not-coming-its-here')
            ->update(['status' => 'past']);

        // ── Panel Session 2 (past: took place 14 July 2026) ─────────────────
        $panel2Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 2',
            'tagline'         => 'AI, Public Services, and the People Left Out',
            'description'     => 'An honest conversation about how AI is being adopted across healthcare, public institutions, and essential services, who is benefiting from that shift, and who is being overlooked or actively harmed by it.',
            'event_date'      => '2026-07-14 18:30:00',
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => 'https://www.youtube.com/live/y64Zw2c42Hs',
            'status'          => 'past',
            'sort_order'      => 2,
        ];

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-2-ai-public-services-and-the-people-left-out'],
            $panel2Attributes
        );

        // ── Speakers (final lineup: Bola, Miriam, Yinka in run-of-show order) ─
        // Metra Rowe was announced on the pre-event flyer but was replaced by
        // Miriam Fearon on the night. The sync() call below is authoritative:
        // any speaker attached to Panel 2 that is not in this list gets
        // detached automatically, so the DB always matches the actual lineup.
        $speakers = [
            [
                'name'         => 'Dr Bola John FRSA',
                'title'        => 'Founder, New Roots Strong Wings CIC',
                'company'      => 'New Roots Strong Wings CIC',
                'bio'          => 'Dr Bola John FRSA is a Medical Doctor and Social Impact Advocate, and Founder of New Roots Strong Wings CIC. Her work focuses on the human impact of algorithmic decision-making in health and social services, and on the community-level responses that keep people visible when automated systems overlook them.',
                'photo_path'   => 'images/speakers/bola-john.jpg',
                'linkedin_url' => null,
                'topic'        => 'The human impact of algorithmic decision-making',
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Miriam Fearon',
                'title'        => 'Customer Success Leader · Breast Cancer Care Patient Advocate',
                'company'      => null,
                'bio'          => 'Miriam Fearon is a Customer Success Leader in tech and a Breast Cancer Care Patient Advocate. She brings a rare dual perspective to this conversation: what it actually feels like to navigate AI-assisted healthcare decisions from the patient side, informed by a working knowledge of how the technology functions.',
                'photo_path'   => 'images/speakers/miriam-fearon.jpg',
                'linkedin_url' => null,
                'topic'        => 'The patient advocate and AI practitioner lens',
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Dr Yinka Laosebikan',
                'title'        => 'MD and CEO, Medihealth International',
                'company'      => 'Medihealth International',
                'bio'          => 'Dr Yinka Laosebikan is a Healthcare Entrepreneur and Digital Health Pioneer, and MD and CEO of Medihealth International. His work sits where clinical care meets digital transformation, and he brings a practitioner view on where AI is genuinely helping patients and where it is quietly leaving them behind.',
                'photo_path'   => 'images/speakers/yinka-laosebikan.jpg',
                'linkedin_url' => null,
                'topic'        => 'AI adoption inside healthcare and digital exclusion',
                'sort_order'   => 3,
            ],
        ];

        // Build sync payload and let sync() detach anyone no longer on the list.
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

        // Clean up: if Metra Rowe now has no panels attached, delete the row.
        $metra = PanelSpeaker::where('name', 'Metra Rowe')->first();
        if ($metra && $metra->sessions()->count() === 0) {
            $metra->delete();
        }

        // ── Attach recording video for past-sessions archive ─────────────────
        PanelMedia::updateOrCreate(
            [
                'panel_session_id' => $session->id,
                'type'             => 'video',
                'url'              => 'https://www.youtube.com/live/y64Zw2c42Hs',
            ],
            [
                'caption'    => 'Full recording: AI, Public Services, and the People Left Out',
                'sort_order' => 1,
            ]
        );

        $this->command->info('Panel 2 seeded: ' . $session->title . ' with ' . count($speakers) . ' speakers. Panel 1 marked as past. Panel 2 marked as past with YouTube recording.');
    }
}
