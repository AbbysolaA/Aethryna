<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use App\Models\SessionPrompt;
use Illuminate\Database\Seeder;

/**
 * Abisola Areola's Panel 4 build, Vibe Coding: Live Builds (20 October
 * 2026): a job application assistant that cannot invent facts.
 *
 * One entry per thing to copy, in order, so the Copy button gives exactly
 * one prompt or file at a time. Guidance beside a prompt ("Real use",
 * "DEMO ONLY", what happened in the live build) goes in the note, which is
 * shown but never copied: pasted into an AI tool it would become part of
 * the instruction. Texts live in database/seeders/prompts/abisola/.
 *
 * Keyed by title, so reruns update in place. Sort orders start at 30,
 * leaving 1 to 29 free for Omonigho's and Ore's builds.
 *
 * Re-runnable:
 *   php artisan db:seed --class=Panel4PromptsSeeder --force
 */
class Panel4PromptsSeeder extends Seeder
{
    public function run(): void
    {
        $panel = PanelSession::where('slug', 'panel-4')->first();

        if (! $panel) {
            return;
        }

        $abisola = PanelSpeaker::where('name', 'Abisola Areola')->value('id');

        $prompts = [
            ['00-start-here.txt', 'Job application assistant: start here', null],
            ['01-evidence-bank.txt', 'Step 1: turn your CV into an evidence bank (me.md)',
                "Real use: answer the questions at the end yourself, with facts you can back up, and ask the AI to update the lines. Anything you cannot evidence stays marked NEEDS DETAIL.\n\nDEMO ONLY (fictional CV): the live build added \"It is intentional that the name is not included, it is a name-blind application. You can use any made-up company name for the purpose of the demo. Fill in the NEEDS DETAIL gaps with made-up details too. Tag every changed line [made-up].\" Never use that with a real CV."],
            ['02-first-three-rules.txt', 'Step 2: write the first three rules',
                'In the demo, the AI asked three questions and these were the answers given: use only the fact part of a NEEDS DETAIL line; reword but never calculate; "partial" means some named parts of the requirement are met.'],
            ['03-three-more-rules.txt', 'Step 3: add three more rules', null],
            ['04-rules-md.txt', 'The finished rules.md',
                'You can save this as rules.md directly instead of generating it in steps 2 and 3.'],
            ['05-create-claude-md.txt', 'Step 4: create CLAUDE.md, so the AI acts as the assistant', null],
            ['06-claude-md.txt', 'The finished CLAUDE.md',
                'You can save this as CLAUDE.md directly instead of generating it in step 4.'],
            ['07-save-advert.txt', 'Step 5: save the job advert',
                'Attach or paste the advert text with this prompt. The sample advert at the end of this list works if you want to practise first.'],
            ['08-write-application.txt', 'Step 6: write the application', null],
            ['09-check-own-work.txt', 'Step 7: make the AI check its own work',
                'In the live build this caught a real slip: two claims used the present tense ("I run", "I present") where the evidence line said "ran" and "presented", which quietly adds the fact that the work is ongoing. Checking is part of the method, not an extra.'],
            ['10-gaps-to-questions.txt', 'Step 8: turn gaps into questions',
                "Use this with one gap at a time. For real evidence, the AI should also tell you what to collect, such as committee minutes, HR records or a reference.\n\nDEMO ONLY: the live build added \"in this instance, make up the data that supports this for the sake of demo\", because the CV was fictional. Do not use that line with your real CV."],
            ['11-update-and-redraft.txt', 'Step 9: update the evidence bank and redraft',
                'In the demo, coverage moved from 3 covered, 6 partial and 7 gaps to 6 covered, 6 partial and 4 gaps, and five of the six Essential criteria became covered.'],
            ['12-tracker-format.txt', 'Step 10 (optional): draft the tracker format',
                'Steps 10 to 12 are optional. The live session ran as a 20-minute demo and these prompts were a separate extra, written out in full here from the same brief. Adjust them to taste.'],
            ['13-approve-tracker.txt', 'Step 10, continued: approve the tracker format',
                'Send this once you have reviewed results-format.md and are happy with it.'],
            ['14-design-system.txt', 'Step 11 (optional): create the design system', null],
            ['15-dashboard.txt', 'Step 12 (optional): build the dashboard',
                'In the live build, the finished state for the sample application was: 6 covered, 6 partial and 4 gaps out of 16 requirements; essential coverage of 91.7%; 9 open gaps; 11 claims, all passing; and a 148-word statement.'],
            ['16-sample-advert.txt', 'A sample advert to practise with',
                'Save this as job-advert.md to try the whole method without using a real application. The employer is fictional.'],
        ];

        foreach ($prompts as $i => [$file, $title, $note]) {
            SessionPrompt::updateOrCreate(
                ['panel_session_id' => $panel->id, 'title' => $title],
                [
                    'panel_speaker_id' => $abisola,
                    'note'             => $note,
                    'body'             => rtrim(file_get_contents(__DIR__.'/prompts/abisola/'.$file)),
                    'sort_order'       => 30 + $i,
                ]
            );
        }
    }
}
