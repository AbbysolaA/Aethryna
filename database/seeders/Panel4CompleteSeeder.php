<?php

namespace Database\Seeders;

use App\Models\PanelMedia;
use App\Models\PanelSession;
use Illuminate\Database\Seeder;

/**
 * Panel 4, Vibe Coding: Live Builds, happened on 20 October 2026.
 *
 * Marking it past does three things at once: its page becomes the archive
 * with the recording embedded, the sessions index stops offering its
 * registration, and the founders session becomes the featured upcoming
 * panel with the main registration form.
 *
 * Re-runnable:
 *   php artisan db:seed --class=Panel4CompleteSeeder --force
 */
class Panel4CompleteSeeder extends Seeder
{
    private const RECORDING = 'https://www.youtube.com/live/41qj4avx-Pg';

    public function run(): void
    {
        $panel = PanelSession::where('slug', 'panel-4')->first();

        if (! $panel) {
            return;
        }

        $panel->update([
            'status'        => 'past',
            'recording_url' => self::RECORDING,
        ]);

        // The page embeds from panel_media, not from the column: same sync
        // the panels admin performs when a recording is saved there.
        $panel->media()->where('type', 'video')
            ->where('url', '!=', self::RECORDING)
            ->delete();

        PanelMedia::updateOrCreate(
            [
                'panel_session_id' => $panel->id,
                'type'             => 'video',
                'url'              => self::RECORDING,
            ],
            [
                'caption'    => 'Full recording: '.$panel->tagline,
                'sort_order' => 1,
            ]
        );

        /*
         * The founder's write-ups of what each person actually built, shown
         * under their card on the archive page and in the recap email. Set
         * by name and only for speakers attached to this panel, so a missing
         * speaker is skipped rather than invented.
         */
        $blurbs = [
            'Omonigho Okome' => 'From Prompting to Context Engineering: Building an AI that knows your product. The same product request, twice: once as a simple prompt, once with her context loaded. Watch how much changes.',
            'Ore Badmus'     => 'Building a research analysis tool with Claude, with no code. A work problem turned into a working tool, one stage at a time, with a scoring framework she wrote herself.',
            'Abisola Areola' => 'Building an AI job application assistant. Turn a resume into an evidence bank, match it to a job advert, and write a tailored resume and cover letter where every claim traces back to evidence.',
        ];

        foreach ($blurbs as $name => $blurb) {
            $speaker = $panel->speakers()->where('name', $name)->first();

            if ($speaker) {
                $panel->speakers()->updateExistingPivot($speaker->id, ['topic' => $blurb]);
            }
        }

        // Her own wording for the role, from the recap copy.
        \App\Models\PanelSpeaker::where('name', 'Omonigho Okome')
            ->where('title', 'Product Manager')
            ->update(['title' => 'Fintech Product Manager']);
    }
}
