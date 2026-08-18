<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 4 — coming soon placeholder.
 *
 * Keeps the sessions page pointing forward between panels. With Panel 3 in
 * the archive and nothing upcoming, the page reads as though the series has
 * finished, which is the opposite of what it should say.
 *
 * The date is deliberately null. event_date is nullable precisely so this
 * card can exist before a date is committed to, rather than publishing a
 * placeholder date for a real public event. The page renders "Date to be
 * announced" until a real one is set.
 *
 * When the details are confirmed:
 *   1. Set event_date, tagline and description below.
 *   2. Add the speakers to the $speakers array.
 *   3. php artisan db:seed --class=Panel4Seeder --force
 */
class Panel4Seeder extends Seeder
{
    public function run(): void
    {
        // Mark Panel 3 as past in case this runs standalone.
        PanelSession::where('slug', 'panel-3-the-data-skills-gap')
            ->where('status', '!=', 'past')
            ->update(['status' => 'past']);

        $panel4Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 4',
            'tagline'         => 'Panel 4 · Coming soon',
            'description'     => 'The next Skills Co-op Sessions panel is being planned. Topic, date and speakers announced soon. Reserve your spot below and you will be first to hear when the details land.',
            'event_date'      => null,   // set once the date is confirmed
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => null,
            'status'          => 'upcoming',
            'sort_order'      => 4,
        ];

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-4-coming-soon'],
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
