<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Adeyinka Prime confirmed for Founders Build Live (10 November 2026), so
 * he joins the published lineup. Panel4SplitSeeder deliberately left him
 * off while his confirmation was outstanding.
 *
 * Deliberately touches nothing but him: no panel attributes, no other
 * speakers, so admin edits made since the split seeder ran all survive.
 *
 * Re-runnable:
 *   php artisan db:seed --class=AdeyinkaPrimeSeeder --force
 */
class AdeyinkaPrimeSeeder extends Seeder
{
    public function run(): void
    {
        $founders = PanelSession::where('slug', 'founders-build-live')->first();

        if (! $founders) {
            return;
        }

        // firstOrCreate by name, because accepting a pitch from the admin
        // also mints a PanelSpeaker and this must not create a twin.
        $adeyinka = PanelSpeaker::firstOrCreate(
            ['name' => 'Adeyinka Prime'],
            ['title' => 'Team Lead', 'bio' => 'Team Lead at Distro.']
        );

        $adeyinka->update([
            'title'        => 'Team Lead',
            'company'      => 'Distro',
            'bio'          => 'Adeyinka leads the team at Distro. At this session he is building WorkProof AI, an AI career agent that reads a CV, maps the skills in it including the transferable ones, and recommends jobs that match what someone can actually do rather than the titles they have held.',
            'linkedin_url' => 'https://www.linkedin.com/in/adefila-adeyinka/',
            // Committed to the repo alongside this seeder, already web-sized.
            'photo_path'   => 'images/speakers/adeyinka-prime.jpg',
        ]);

        $founders->speakers()->syncWithoutDetaching([
            $adeyinka->id => [
                'topic'      => 'WorkProof AI: Your Experience, Your Skills, Your Next Opportunity',
                'sort_order' => 2,
            ],
        ]);
    }
}
