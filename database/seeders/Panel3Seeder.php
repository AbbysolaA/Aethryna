<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 3 seeder.
 *
 * This currently seeds a lightweight "Coming soon" placeholder card so the
 * sessions page shows that Panel 3 is being planned. When the real details
 * are confirmed, replace the placeholder values below with the real ones
 * and re-run: php artisan db:seed --class=Panel3Seeder --force
 *
 * When you add real speakers, uncomment the speaker template block and
 * duplicate it per confirmed speaker.
 */
class Panel3Seeder extends Seeder
{
    public function run(): void
    {
        // Mark Panel 2 as past just in case Panel3Seeder runs standalone.
        PanelSession::where('slug', 'panel-2-ai-public-services-and-the-people-left-out')
            ->where('status', '!=', 'past')
            ->update(['status' => 'past']);

        // ── Panel Session 3 ──────────────────────────────────────────────────
        // Placeholder values until the real details are confirmed. The site
        // treats an upcoming panel with no speakers as a coming-soon card and
        // hides the metadata band (see sessions.blade.php).
        $panel3Attributes = [
            'title'           => 'The Skills Co-op Sessions: Panel 3',
            'tagline'         => 'Panel 3 · Coming soon',
            'description'     => 'The next Skills Co-op Sessions panel is being planned. Topic, date, and speakers announced soon. Reserve your spot below to be first to hear when the details land.',
            'event_date'      => '2026-09-30 18:30:00',   // placeholder target; update when confirmed
            'duration'        => '60 minutes',
            'format'          => 'Online',
            'eventbrite_url'  => null,
            'recording_url'   => null,
            'status'          => 'upcoming',
            'sort_order'      => 3,
        ];

        // Safety guard: refuse to seed if the tagline is still one of the
        // legacy TODO placeholder strings. This runs against the tagline
        // itself, not a literal string, so it does not fire on legitimate
        // "Coming soon" content.
        if (str_starts_with($panel3Attributes['tagline'], 'TODO:')) {
            $this->command->warn('Panel 3 still has TODO placeholders in the tagline. Skipping seed. Fill in the details and re-run.');
            return;
        }

        $session = PanelSession::updateOrCreate(
            ['slug' => 'panel-3-coming-soon'],
            $panel3Attributes
        );

        // ── Speakers ─────────────────────────────────────────────────────────
        // Uncomment and duplicate this block per confirmed speaker. Keep
        // sort_order sequential (1, 2, 3, ...) so they render in the intended
        // order. Using sync() below means removing a speaker from this list
        // detaches them from the panel on the next seed run.
        $speakers = [
            /*
            [
                'name'         => 'TODO Speaker Name',
                'title'        => 'TODO Job title',
                'company'      => 'TODO Company (or null)',
                'bio'          => 'TODO Short bio, one paragraph.',
                'photo_path'   => 'images/speakers/todo-slug.jpg',
                'linkedin_url' => null,
                'topic'        => 'TODO What this speaker will speak to',
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

        // Clean up: remove the old placeholder slug from the initial scaffold
        // if it happens to be in the DB.
        PanelSession::where('slug', 'panel-3-TODO-slug')->delete();

        $this->command->info('Panel 3 seeded: ' . $session->title . ' with ' . count($speakers) . ' speakers.');
    }
}
