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
    }
}
