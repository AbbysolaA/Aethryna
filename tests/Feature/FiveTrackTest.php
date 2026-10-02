<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Pathway;
use Database\Seeders\AspirationQuestionSeeder;
use Database\Seeders\PathwaysSeeder;
use Database\Seeders\QuestionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The five-track update, pinned where it can hurt: the assessment must never
 * again recommend a track that does not run in January 2027, and every public
 * surface must say five and agree with the others.
 */
class FiveTrackTest extends TestCase
{
    use RefreshDatabase;

    private const FOUNDING_SLUGS = [
        'project-management',
        'product-management',
        'data-analytics',
        'ui-ux-design',
        'software-development-foundations',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PathwaysSeeder::class);
        $this->seed(QuestionsSeeder::class);
        $this->seed(AspirationQuestionSeeder::class);
        Mail::fake();
    }

    /**
     * Drive an assessment to completion with the scores forced to a known
     * shape, answering only the final aspiration question for real.
     */
    private function completeWith(array $scores, string $aspirationLabel): Assessment
    {
        $this->post('/assessment/start');
        $assessment = Assessment::latest('id')->firstOrFail();

        $assessment->update(['scores' => $scores]);

        $this->post('/assessment/question/16/answer', ['answer' => $aspirationLabel])
            ->assertRedirect(route('assessment.results'));

        return $assessment->fresh();
    }

    public function test_every_recommendation_is_a_founding_track(): void
    {
        $this->completeWith(['T' => 6, 'C' => 1, 'B' => 2, 'S' => 4, 'F' => 0], 'E');

        $slugs = AssessmentResult::with('pathway')->get()->pluck('pathway.slug');

        $this->assertNotEmpty($slugs);
        foreach ($slugs as $slug) {
            $this->assertContains($slug, self::FOUNDING_SLUGS);
        }
    }

    /**
     * The old resolution's exact failure: a technical match was handed Web
     * Development, which does not run. Technical now resolves to Software
     * Development with Data and AI Analytics alongside.
     */
    public function test_a_technical_match_gets_software_development_not_web_development(): void
    {
        $this->completeWith(['T' => 8, 'C' => 0, 'B' => 1, 'S' => 2, 'F' => 0], 'E');

        $primary = AssessmentResult::where('result_type', 'primary')->first();
        $secondary = AssessmentResult::where('result_type', 'secondary')->first();

        $this->assertSame('software-development-foundations', $primary->pathway->slug);
        $this->assertSame('data-analytics', $secondary->pathway->slug);
    }

    /**
     * Stated interest beats weighted familiarity: business-heavy answers with
     * an aspiration for Software Development lead with the aspiration.
     */
    public function test_the_aspiration_answer_wins_over_the_clusters(): void
    {
        $this->completeWith(['T' => 0, 'C' => 1, 'B' => 8, 'S' => 0, 'F' => 2], 'E');

        $primary = AssessmentResult::where('result_type', 'primary')->first();
        $secondary = AssessmentResult::where('result_type', 'secondary')->first();

        $this->assertSame('software-development-foundations', $primary->pathway->slug);
        $this->assertSame('project-management', $secondary->pathway->slug);
    }

    public function test_a_security_match_is_told_about_cohort_two(): void
    {
        $this->completeWith(['T' => 2, 'C' => 0, 'B' => 1, 'S' => 8, 'F' => 0], 'C');

        $primary = AssessmentResult::where('result_type', 'primary')->first();

        $this->assertSame('data-analytics', $primary->pathway->slug);
        $this->assertStringContainsString('Cyber Security opens with our second cohort', $primary->recommendation_text);
    }

    public function test_the_results_page_labels_the_secondary_honestly(): void
    {
        $this->completeWith(['T' => 6, 'C' => 2, 'B' => 1, 'S' => 0, 'F' => 0], 'E');

        $this->get('/assessment/results')
            ->assertOk()
            ->assertSee('Also worth considering')
            ->assertDontSee('Secondary Option');
    }

    public function test_the_seeder_flags_exactly_the_five_founding_tracks(): void
    {
        $pilots = Pathway::where('is_pilot', true)->pluck('slug')->sort()->values()->all();

        $this->assertSame(collect(self::FOUNDING_SLUGS)->sort()->values()->all(), $pilots);
        $this->assertSame('Project Management and Delivery', Pathway::where('slug', 'project-management')->value('name'));
        $this->assertSame('Product Design and Marketing', Pathway::where('slug', 'ui-ux-design')->value('name'));
        // Nothing deleted: the full catalogue survives the update.
        $this->assertSame(17, Pathway::count());
    }

    public function test_the_homepage_shows_five_cards_that_agree_with_everything_else(): void
    {
        $response = $this->get('/')->assertOk();

        $response->assertSee('Five pilot tracks, AI tools embedded throughout')
            ->assertSee('Product Management')
            ->assertSee('Project Management and Delivery')
            ->assertSee('data-target="5"', false)
            ->assertSee('Eleven tracks in our full catalogue')
            ->assertSee('Cyber Security, Cloud and DevOps, Tech Sales and Customer Success and IT Support and Operations');

        foreach (self::FOUNDING_SLUGS as $slug) {
            $response->assertSee('/programs/'.$slug, false);
        }
    }

    public function test_the_footer_links_resolve_for_all_five_tracks(): void
    {
        foreach (self::FOUNDING_SLUGS as $slug) {
            $this->get('/programs/'.$slug)->assertOk();
        }
    }

    public function test_a_founding_track_page_carries_the_handoff_sections(): void
    {
        $this->get('/programs/project-management')
            ->assertOk()
            ->assertSee('Project Management and Delivery')
            ->assertSee('Be the person who gets digital work delivered.')
            ->assertSee('What you will learn')
            ->assertSee('What you will make')
            ->assertSee('How the programme works')
            ->assertSee('Apply for January 2027')
            ->assertSee('Not sure? Take the assessment');
    }

    public function test_web_development_points_at_the_founding_route(): void
    {
        $this->get('/programs/web-development')
            ->assertOk()
            ->assertSee('For the founding cohort, this route runs as')
            ->assertSee('/programs/software-development-foundations', false);
    }

    public function test_non_pilot_pages_still_render(): void
    {
        foreach (['cybersecurity', 'cloud-devops', 'it-support'] as $slug) {
            if (Pathway::where('slug', $slug)->exists()) {
                $this->get('/programs/'.$slug)->assertOk();
            }
        }

        $this->assertGreaterThan(0, Pathway::where('is_pilot', false)->count());
    }
}
