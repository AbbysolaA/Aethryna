<?php

namespace Database\Seeders;

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

        // ── Panel Session 2 ──────────────────────────────────────────────────
        $session = PanelSession::firstOrCreate(
            ['slug' => 'panel-2-ai-public-services-and-the-people-left-out'],
            [
                'title'           => 'The Skills Co-op Sessions: Panel 2',
                'tagline'         => 'AI, Public Services, and the People Left Out',
                'description'     => 'An honest conversation about how AI is being adopted across healthcare, public institutions, and essential services, who is benefiting from that shift, and who is being overlooked or actively harmed by it.',
                'event_date'      => '2026-07-14 18:30:00',
                'duration'        => '60 minutes',
                'format'          => 'Online',
                'eventbrite_url'  => null,
                'recording_url'   => null,
                'status'          => 'upcoming',
                'sort_order'      => 2,
            ]
        );

        // ── Speakers ─────────────────────────────────────────────────────────
        $speakers = [
            [
                'name'         => 'Metra Rowe',
                'title'        => 'Inclusive Culture Change Advisor',
                'company'      => 'Starling Business Solutions',
                'bio'          => 'Metra Rowe is an Inclusive Culture Change Advisor and Founder of Starling Business Solutions. She works with organisations to shift culture, systems, and leadership behaviour so that inclusion is not a policy statement but a daily practice, particularly as AI-driven decisions reshape who gets seen and who does not.',
                'photo_path'   => 'images/speakers/metra-rowe.jpg',
                'linkedin_url' => null,
                'topic'        => 'Bias and equity in automated public services',
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Dr Yinka Laosebikan',
                'title'        => 'MD and CEO, Medihealth International',
                'company'      => 'Medihealth International',
                'bio'          => 'Dr Yinka Laosebikan is a Healthcare Entrepreneur and Digital Health Pioneer, and MD and CEO of Medihealth International. His work sits at the intersection of clinical care and digital transformation, and he brings a practitioner view on where AI is genuinely helping patients and where it is quietly leaving them behind.',
                'photo_path'   => 'images/speakers/yinka-laosebikan.jpg',
                'linkedin_url' => null,
                'topic'        => 'AI adoption inside healthcare and digital exclusion',
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Dr Bola John FRSA',
                'title'        => 'Founder, New Roots Strong Wings CIC',
                'company'      => 'New Roots Strong Wings CIC',
                'bio'          => 'Dr Bola John FRSA is a Medical Doctor and Social Impact Advocate, and Founder of New Roots Strong Wings CIC. Her work focuses on the human impact of algorithmic decision-making in health and social services, and on the community-level responses that keep people visible when automated systems overlook them.',
                'photo_path'   => 'images/speakers/bola-john.jpg',
                'linkedin_url' => null,
                'topic'        => 'The human impact of algorithmic decision-making',
                'sort_order'   => 3,
            ],
        ];

        foreach ($speakers as $data) {
            $topic      = $data['topic'];
            $sort_order = $data['sort_order'];
            unset($data['topic'], $data['sort_order']);

            $speaker = PanelSpeaker::firstOrCreate(
                ['name' => $data['name']],
                $data
            );

            if (! $session->speakers()->where('panel_speaker_id', $speaker->id)->exists()) {
                $session->speakers()->attach($speaker->id, [
                    'topic'      => $topic,
                    'sort_order' => $sort_order,
                ]);
            }
        }

        $this->command->info('Panel 2 seeded: ' . $session->title . ' with ' . count($speakers) . ' speakers. Panel 1 marked as past.');
    }
}
