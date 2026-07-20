<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 3 scaffold.
 *
 * Fill in the TODO markers below when the panel details are confirmed:
 *   - slug (kebab-case, must be unique)
 *   - tagline (the panel topic, shown as the h2 on the sessions page)
 *   - description (2 to 4 sentences)
 *   - event_date (Y-m-d H:i:s, London time)
 *   - eventbrite_url (once tickets go live)
 *   - speakers array (name, title, company, bio, photo_path, linkedin_url, topic)
 *
 * When ready, run: php artisan db:seed --class=Panel3Seeder --force
 */
class Panel3Seeder extends Seeder
{
    public function run(): void
    {
        // ── Mark Panel 2 as past just in case Panel3Seeder runs standalone ──
        PanelSession::where('slug', 'panel-2-ai-public-services-and-the-people-left-out')
            ->where('status', '!=', 'past')
            ->update(['status' => 'past']);

        // ── Panel Session 3 ──────────────────────────────────────────────────
        $panel3Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 3',
            'tagline'         => 'TODO: Panel 3 topic (short, punchy — becomes the h2 on the sessions page)',
            'description'     => 'TODO: 2 to 4 sentences describing what the panel will cover, who it is for, and what listeners will take away.',
            'event_date'      => '2026-08-11 18:30:00', // TODO: confirm date and time (Europe/London)
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,                  // TODO: paste Eventbrite URL when live
            'recording_url'   => null,                  // fill in after the event
            'status'          => 'upcoming',
            'sort_order'      => 3,
        ];

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-3-TODO-slug'],            // TODO: change to real slug
            $panel3Attributes
        );

        // ── Speakers ─────────────────────────────────────────────────────────
        // Duplicate the block below for each confirmed speaker. Keep sort_order
        // sequential (1, 2, 3, ...) so they render in the intended order.
        $speakers = [
            /*
            [
                'name'         => 'TODO Speaker Name',
                'title'        => 'TODO Job title',
                'company'      => 'TODO Company (or null)',
                'bio'          => 'TODO Short bio, one paragraph.',
                'photo_path'   => 'images/speakers/todo-slug.jpg',  // upload to public/images/speakers/
                'linkedin_url' => null,                             // or full https URL
                'topic'        => 'TODO What this speaker will speak to',
                'sort_order'   => 1,
            ],
            */
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

        $this->command->info('Panel 3 scaffold seeded. Fill in TODOs, then re-run this seeder to update.');
    }
}
