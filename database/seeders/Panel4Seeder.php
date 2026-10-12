<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 4: Vibe coding, show and tell. Tuesday 20 October 2026, online.
 *
 * The September date this panel first carried never happened, so the same
 * row moves to October rather than a cancelled one being left beside a new
 * one: the URL keeps working and anyone already registered stays registered
 * against the panel they will actually attend.
 *
 * 18:30 on a Tuesday, matching Panels 1, 2 and 3; the flyer names the date
 * and not the time, so the series' own habit fills it in. Change it in the
 * panels admin if the flyer's final version says otherwise.
 *
 * Speakers come through /apply-to-speak this time; add confirmed ones to the
 * $speakers array and re-run:
 *   php artisan db:seed --class=Panel4Seeder --force
 */
class Panel4Seeder extends Seeder
{
    public function run(): void
    {
        // Mark Panel 3 as past in case this runs standalone.
        PanelSession::where('slug', 'panel-3-the-data-skills-gap')
            ->where('status', '!=', 'past')
            ->update(['status' => 'past']);

        // The slug was 'panel-4-coming-soon' while the date was unknown, which
        // now reads oddly in a URL people are being asked to share. Renamed
        // rather than left, and moved rather than re-created, so an existing
        // row keeps its registrations instead of being orphaned beside a new
        // one. Safe either way: this is a no-op if the old slug never existed.
        PanelSession::where('slug', 'panel-4-coming-soon')
            ->update(['slug' => 'panel-4']);

        $panel4Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 4',
            // Retitled when Panel 4 split into two sessions. The founders
            // session lives in Panel4SplitSeeder with its own slug.
            'tagline'         => 'Vibe Coding: Live Builds',
            // The flyer's copy, so the page and the flyer say the same thing.
            'description'     => 'Three practitioners. Three live builds. Watch them go from a real problem to a working AI-built solution on screen, with the prompts, wrong turns and judgement calls included, then put your questions to them. We are especially keen to hear from women building in AI. Tuesday 20 October at 6.30pm, online, recorded and shared publicly.',
            'event_date'      => '2026-10-20 18:30:00',
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => null,
            'status'          => 'upcoming',
            'sort_order'      => 4,
            // The running order shown on the panel page. Editable in the
            // panels admin; a line's "time |" prefix becomes the schedule
            // column.
            'itinerary'       => [
                '6.30pm | Doors open and welcome',
                '6.35pm | Live builds: the panel shows how they actually work with AI',
                '7.10pm | Audience Q&A, hard questions welcome',
                '7.25pm | What is next at Skills Co-op and goodnight',
            ],
            'accessibility_note' => 'Live captions are available throughout. If you need anything else to take part, email hello@skillscoop.org and we will sort it.',
            // The flyer, committed to the repo so the page ships with it.
            // Replacing it later goes through the panels admin, which writes
            // its own file and updates this path.
            'banner_path'        => 'panel-4.jpg',
        ];

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-4'],
            $panel4Attributes
        );

        // ── Speakers ─────────────────────────────────────────────────────────
        // Empty until the lineup is confirmed. The sessions page treats an
        // upcoming panel with no speakers as a coming-soon card and hides the
        // speaker grid. sync() is authoritative: anyone removed from this list
        // is detached from the panel on the next run.
        $speakers = [
            /*
            [
                'name'         => 'Speaker Name',
                'title'        => 'Job title, without the employer — company goes in its own field',
                'company'      => 'Company (or null)',
                'bio'          => 'Short bio, one paragraph.',
                'photo_path'   => 'images/speakers/firstname-lastname.jpg',
                'linkedin_url' => null,
                'topic'        => 'What this speaker will speak to',
                'sort_order'   => 1,
            ],
            */
        ];

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

        $this->command->info(
            'Panel 4 seeded: coming-soon card, '
            . ($session->event_date ? $session->event_date->format('j F Y') : 'no date yet')
            . ', ' . count($speakers) . ' speakers.'
        );
    }
}
