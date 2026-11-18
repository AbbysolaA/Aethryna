<?php

namespace Database\Seeders;

use App\Models\PanelMedia;
use App\Models\PanelSession;
use Illuminate\Database\Seeder;

/**
 * Founders Build Live happened on Wednesday 18 November 2026.
 *
 * Marked past with the recording embedded, so its page becomes the archive
 * and panels:recap founders-build-live can send it to the whole bank.
 * Speakers, topics and build notes are left exactly as the lineup seeder
 * and any admin edits set them.
 *
 * Re-runnable:
 *   php artisan db:seed --class=FoundersCompleteSeeder --force
 */
class FoundersCompleteSeeder extends Seeder
{
    // The share link's ?si= tracking parameter is dropped on purpose.
    private const RECORDING = 'https://www.youtube.com/live/rhL-iNy403s';

    public function run(): void
    {
        $panel = PanelSession::where('slug', 'founders-build-live')->first();

        if (! $panel) {
            return;
        }

        $panel->update([
            'status'        => 'past',
            'recording_url' => self::RECORDING,
        ]);

        // The page embeds from panel_media, not from the column: the same
        // sync the panels admin performs when a recording is saved there.
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
