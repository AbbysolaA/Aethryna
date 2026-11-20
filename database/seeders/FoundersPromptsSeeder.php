<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use App\Models\SessionPrompt;
use Illuminate\Database\Seeder;

/**
 * The prompts and build documents from Founders Build Live, as the speakers
 * supplied them. The texts live in database/seeders/prompts/ so they can be
 * read and diffed as documents rather than as PHP strings.
 *
 * Keyed by title, so a rerun updates the text in place and never
 * duplicates. Sort orders start at 10, leaving 1 to 9 free for Dr Ayse's
 * prompts, which come first on the night.
 *
 * Re-runnable:
 *   php artisan db:seed --class=FoundersPromptsSeeder --force
 */
class FoundersPromptsSeeder extends Seeder
{
    public function run(): void
    {
        $panel = PanelSession::where('slug', 'founders-build-live')->first();

        if (! $panel) {
            return;
        }

        $speakerId = fn (array $names) => PanelSpeaker::whereIn('name', $names)->value('id');

        $bola = $speakerId(['Bola Naomi Soko', 'Bola Soko']);
        $adeyinka = $speakerId(['Adeyinka Prime']);

        $prompts = [
            [
                'title'      => 'Certificate Studio: the full build prompt',
                'speaker'    => $bola,
                'file'       => 'bola-certificate-studio.txt',
                'sort_order' => 10,
            ],
            [
                'title'      => 'WorkProof AI: participant guide (start here)',
                'speaker'    => $adeyinka,
                'file'       => 'adeyinka-workproof-guide.txt',
                'sort_order' => 20,
            ],
            [
                'title'      => 'WorkProof AI: the build prompt (paste this into your coding agent)',
                'speaker'    => $adeyinka,
                'file'       => 'adeyinka-workproof-build-prompt.txt',
                'sort_order' => 21,
            ],
            [
                'title'      => 'WorkProof AI: product requirements (attach this to your coding agent)',
                'speaker'    => $adeyinka,
                'file'       => 'adeyinka-workproof-prd.txt',
                'sort_order' => 22,
            ],
        ];

        foreach ($prompts as $prompt) {
            SessionPrompt::updateOrCreate(
                ['panel_session_id' => $panel->id, 'title' => $prompt['title']],
                [
                    'panel_speaker_id' => $prompt['speaker'],
                    'body'             => rtrim(file_get_contents(__DIR__.'/prompts/'.$prompt['file'])),
                    'sort_order'       => $prompt['sort_order'],
                ]
            );
        }
    }
}
