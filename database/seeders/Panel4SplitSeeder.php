<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * Panel 4 split into two sessions, neither labelled A or B publicly:
 *
 *   Vibe Coding: Live Builds          Tuesday 20 October 2026  (slug panel-4)
 *   Vibe Coding: Founders Build Live  Tuesday 10 November 2026 (slug founders-build-live)
 *
 * The existing panel-4 row is retitled rather than replaced, so its
 * registrations, printed links and banner all survive. Both carry
 * sort_order 4 on purpose: they are the same series number, and upcoming
 * ordering runs on event_date.
 *
 * Re-runnable:
 *   php artisan db:seed --class=Panel4SplitSeeder --force
 */
class Panel4SplitSeeder extends Seeder
{
    public function run(): void
    {
        // The October session keeps everything, under its new name.
        PanelSession::where('slug', 'panel-4')->update([
            'tagline' => 'Vibe Coding: Live Builds',
        ]);

        // The November founders session. 6.30pm to 8pm UK time; the clocks
        // have changed by then, so UK wall clock is GMT and the schema's
        // +00:00 offset falls out of the usual Europe/London shift.
        $founders = PanelSession::updateOrCreate(
            ['slug' => 'founders-build-live'],
            [
                'title'       => 'The Skills Co-op Sessions: Panel 4',
                'tagline'     => 'Vibe Coding: Founders Build Live',
                'description' => "Panel 4 is a live-build session. Instead of talking about AI, founders build with it on screen, going from a real problem to a working solution in front of you. You'll see the prompts, the wrong turns and the judgement calls, not a polished demo. After the builds, put your questions to the speakers. The session is free, online, and recorded and shared publicly afterwards.",
                'event_date'  => '2026-11-10 18:30:00',
                'duration'    => '90 minutes',
                'format'      => 'Online',
                'status'      => 'upcoming',
                'sort_order'  => 4,
                'itinerary'   => [
                    '6.30pm | Doors open and welcome',
                    '6.40pm | Founders build live on screen, one real problem at a time',
                    '7.40pm | Audience Q&A, hard questions welcome',
                    '7.55pm | What is next at Skills Co-op and goodnight',
                ],
                'accessibility_note' => 'Live captions are available throughout. If you need anything else to take part, email hello@skillscoop.org and we will sort it.',
            ]
        );

        /*
         * Ayse is the only speaker published for now. Her title and company
         * wording are awaiting her confirmation, so edit the speaker in the
         * panels admin when she replies. Adeyinka and Bola are deliberately
         * not listed: one pitch is outstanding, the other has not replied.
         *
         * firstOrCreate by name, because accepting her pitch from the admin
         * also mints a PanelSpeaker and this must not create a twin.
         */
        $ayse = PanelSpeaker::firstOrCreate(
            ['name' => 'Ayse Basak Cinar'],
            [
                'title'   => 'Leadership Consultant and AI Strategist',
                'company' => 'ABC I To Be the One Consulting',
                'bio'     => 'Leadership consultant and AI strategist helping people turn AI from a buzzword into a working tool.',
            ]
        );

        $founders->speakers()->syncWithoutDetaching([
            $ayse->id => [
                'topic'      => 'Adapting a custom GPT into a reusable AI skill, live',
                'sort_order' => 1,
            ],
        ]);
    }
}
