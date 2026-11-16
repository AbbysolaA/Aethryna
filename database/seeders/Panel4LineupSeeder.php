<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use Illuminate\Database\Seeder;

/**
 * The confirmed Founders Build Live lineup and running order for
 * Wednesday 18 November 2026: Dr Ayse Basak Cinar at 6.35pm, Bola Naomi
 * Soko at 7.00pm, Adeyinka Prime at 7.25pm. Session titles and build
 * descriptions are the speakers' own copy, used as written.
 *
 * Deliberately rewrites the itinerary, the description and these three
 * speakers' details: that is the point of the update. It does not touch
 * the banner, the date or any other panel.
 *
 * Re-runnable:
 *   php artisan db:seed --class=Panel4LineupSeeder --force
 */
class Panel4LineupSeeder extends Seeder
{
    public function run(): void
    {
        $founders = PanelSession::where('slug', 'founders-build-live')->first();

        if (! $founders) {
            return;
        }

        $founders->update([
            'description' => "Panel 4 is a live-build session. Instead of talking about AI, founders build with it on screen, going from a real problem to a working solution in front of you. You'll see the prompts, the wrong turns and the judgement calls, not a polished demo. After the builds, put your questions to the speakers. The session is free, online, and recorded and shared publicly afterwards. Every build uses fictional or sample data only, with nothing personal on screen. Anyone registered for the earlier date is still registered.",
            'itinerary'   => [
                '6.30pm | Doors open and welcome',
                '6.35pm | Dr Ayse Basak Cinar: Building Emotional UX: From AI Companion to Reusable AI Skills',
                '7.00pm | Bola Naomi Soko: App Development for Personal Effectiveness',
                '7.25pm | Adeyinka Prime: WorkProof AI: Your Experience, Your Skills, Your Next Opportunity',
                '7.50pm | Audience Q&A, hard questions welcome',
                '8.00pm | Close',
            ],
        ]);

        // ── Dr Ayse Basak Cinar ──────────────────────────────────────────
        // Her earlier row carries the undoctored name; look both up so a
        // rerun after the rename still finds her and never mints a twin.
        $ayse = PanelSpeaker::whereIn('name', ['Dr Ayse Basak Cinar', 'Ayse Basak Cinar'])->first()
            ?? new PanelSpeaker(['bio' => '']);

        $ayse->fill([
            'name'    => 'Dr Ayse Basak Cinar',
            // The title already names the company, so roleLine() prints it
            // once rather than twice.
            'title'   => 'Founder, ABC Consulting; International Leadership Development Consultant and EQAI Strategist',
            'company' => 'ABC Consulting',
        ])->save();

        // ── Bola Naomi Soko ──────────────────────────────────────────────
        // She spoke at Panel 3 as "Bola Soko"; same row, fuller name now.
        $bola = PanelSpeaker::whereIn('name', ['Bola Naomi Soko', 'Bola Soko'])->first()
            ?? new PanelSpeaker(['bio' => '']);

        $bola->fill([
            'name'    => 'Bola Naomi Soko',
            'title'   => 'Founder',
            'company' => 'Women in AI & Automation',
            'bio'     => 'Two decades in high-stakes procurement, from the London 2012 Olympic build to the Covid Vaccine Taskforce. Joined us for Panel 3.',
        ]);
        $bola->photo_path = $bola->photo_path ?: 'images/speakers/bola-soko.jpg';
        $bola->save();

        // ── Adeyinka Prime ───────────────────────────────────────────────
        $adeyinka = PanelSpeaker::firstOrCreate(
            ['name' => 'Adeyinka Prime'],
            ['title' => 'Team Lead', 'company' => 'Distro', 'bio' => 'Team Lead at Distro.', 'photo_path' => 'images/speakers/adeyinka-prime.jpg']
        );

        // Running order and each speaker's build, their own copy as written.
        // syncWithoutDetaching updates the pivot in place for anyone already
        // attached, which also replaces any older talk title left on the
        // founders pivot from before the lineup was confirmed.
        $founders->speakers()->syncWithoutDetaching([
            $ayse->id => [
                'topic'      => 'Building Emotional UX: From AI Companion to Reusable AI Skills',
                'build_note' => 'This demonstration shows how to design, build and test AI support using a fictional workplace scenario.',
                'sort_order' => 1,
            ],
            $bola->id => [
                'topic'      => 'App Development for Personal Effectiveness',
                'build_note' => 'She builds a certificate generator in Google AI Studio, live from plain-English prompts. Using Google AI Studio to build apps is a brilliant way to boost your personal productivity, as it lets you turn repetitive tasks into custom software using plain English. Rather than wrestling with complex code, you can simply prompt the AI to prototype and deploy tailored tools, such as an automated certificate generator for your project in just a few clicks. Ultimately, it allows you to build digital systems suited to how you work best. The takeaway: how to turn a repetitive task into a custom tool using plain English.',
                'sort_order' => 2,
            ],
            $adeyinka->id => [
                'topic'      => 'WorkProof AI: Your Experience, Your Skills, Your Next Opportunity',
                'build_note' => 'An AI-powered career agent that helps job seekers discover opportunities based on their actual skills and experience, not just their job titles or qualifications. He uploads a fictional CV, has the AI identify the skills in it (including transferable skills), and receives personalised job recommendations, each with an explanation of why it matches the profile. The takeaway: a practical method for building an agent that matches people to opportunities by their skills and experience, not only their job titles.',
                'sort_order' => 3,
            ],
        ]);
    }
}
