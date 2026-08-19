<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 4 — date confirmed, topic and speakers still to come.
 *
 * Keeps the sessions page pointing forward between panels. With Panel 3 in
 * the archive and nothing upcoming, the page reads as though the series has
 * finished, which is the opposite of what it should say.
 *
 * 18:30 on a Tuesday, matching Panels 1, 2 and 3. A series people are meant
 * to keep turning up to is worth keeping predictable.
 *
 * The card now leads with the date and says plainly that the topic is still
 * being confirmed, rather than claiming nothing is known. Somebody can put it
 * in their diary today and find out what it is about later; the reverse is no
 * use to anyone.
 *
 * When the rest is confirmed:
 *   1. Set the title, tagline and description below.
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

        // The slug was 'panel-4-coming-soon' while the date was unknown, which
        // now reads oddly in a URL people are being asked to share. Renamed
        // rather than left, and moved rather than re-created, so an existing
        // row keeps its registrations instead of being orphaned beside a new
        // one. Safe either way: this is a no-op if the old slug never existed.
        PanelSession::where('slug', 'panel-4-coming-soon')
            ->update(['slug' => 'panel-4']);

        $panel4Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 4',
            'tagline'         => 'Panel 4 · Tuesday 15 September',
            'description'     => 'The next Skills Co-op Sessions panel is on Tuesday 15 September at 6:30pm. Topic and speakers are being confirmed — register below and we will email you the moment they are announced, along with the joining link.',
            'event_date'      => '2026-09-15 18:30:00',
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => null,
            'status'          => 'upcoming',
            'sort_order'      => 4,
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
