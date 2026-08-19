<?php

namespace Tests\Feature;

use App\Mail\AssessmentCompleted;
use App\Mail\AssessmentResume;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Pathway;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The identity an assessment carries, and what it lets us do.
 *
 * Nearly everyone takes the assessment without an account, so before this the
 * completers could not be sent their results and the abandoners could not be
 * followed up at all. These cover the three ways an address now arrives —
 * part way through, at the end, or from an account — and the two things it is
 * allowed to be used for.
 */
class AssessmentIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Pathway::create([
            'name' => 'Technical Track', 'slug' => 'technical-track',
            'description' => 'Building things.', 'category' => 'technical',
            'recommended_for' => 'People who like taking things apart.',
            'skills' => ['HTML'], 'career_paths' => ['Junior developer'],
            'difficulty_level' => 'beginner', 'is_active' => true,
        ]);

        foreach (range(1, 3) as $n) {
            $question = Question::create([
                'question_number' => $n,
                'question_text'   => "Question {$n}?",
                'section'         => 'A',
                'order'           => $n,
                'is_active'       => true,
            ]);

            Answer::create([
                'question_id'  => $question->id,
                'option_label' => 'A',
                'answer_text'  => 'First option',
                'clusters'     => ['T'],
                'order'        => 1,
                'is_active'    => true,
            ]);
        }
    }

    /** Walk the assessment to the end, answering every question. */
    private function completeAssessment(): void
    {
        $this->post('/assessment/start');

        foreach (Question::active()->orderBy('question_number')->pluck('question_number') as $n) {
            $this->post("/assessment/question/{$n}/answer", ['answer' => 'A']);
        }
    }

    public function test_an_address_given_part_way_through_gets_a_link_back_in(): void
    {
        Mail::fake();

        $this->post('/assessment/start');
        $this->post('/assessment/question/1/answer', ['answer' => 'A']);

        $this->post('/assessment/save-progress', [
            'contact_name'  => 'Sam Okafor',
            'contact_email' => 'sam@example.test',
        ])->assertSessionHasNoErrors();

        $assessment = Assessment::first();

        $this->assertSame('sam@example.test', $assessment->contact_email);
        $this->assertNotNull($assessment->resume_token, 'A resume link needs a token to point at.');

        Mail::assertSent(AssessmentResume::class, fn ($mail) => $mail->hasTo('sam@example.test')
            && $mail->reason === 'saved');
    }

    public function test_the_resume_link_opens_the_next_unanswered_question_in_a_new_browser(): void
    {
        $this->post('/assessment/start');
        $this->post('/assessment/question/1/answer', ['answer' => 'A']);

        $assessment = Assessment::first();
        $token = $assessment->ensureResumeToken();

        // A different browser entirely: no cookie from the first visit.
        $this->flushSession();

        $this->get("/assessment/resume/{$token}")
            ->assertRedirect(route('assessment.question', ['question' => 2]));
    }

    public function test_a_bad_resume_token_explains_itself_rather_than_erroring(): void
    {
        $this->get('/assessment/resume/not-a-real-token')
            ->assertRedirect(route('assessment.index'))
            ->assertSessionHas('error');
    }

    public function test_an_address_given_on_the_results_page_gets_the_results(): void
    {
        Mail::fake();

        $this->completeAssessment();

        // Nothing sent yet: we had nowhere to send it.
        Mail::assertNothingSent();

        $this->post('/assessment/contact', ['contact_email' => 'rita@example.test'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('assessment.results'));

        Mail::assertSent(AssessmentCompleted::class, fn ($mail) => $mail->hasTo('rita@example.test'));
        $this->assertNotNull(Assessment::first()->results_emailed_at);
    }

    public function test_the_results_are_not_emailed_twice(): void
    {
        Mail::fake();

        $this->completeAssessment();
        $this->post('/assessment/contact', ['contact_email' => 'rita@example.test']);
        $this->post('/assessment/contact', ['contact_email' => 'rita@example.test']);

        Mail::assertSentCount(1);
    }

    public function test_logging_in_claims_the_assessment_taken_before_the_account_existed(): void
    {
        $user = User::factory()->create(['email' => 'lena@example.test']);

        // One taken days ago on another device, identified only by the address
        // typed into the assessment itself.
        $earlier = Assessment::create([
            'session_id' => 'some-old-session',
            'status' => 'completed',
            'contact_email' => 'lena@example.test',
            'responses' => [], 'scores' => [],
            'started_at' => now()->subDays(3), 'completed_at' => now()->subDays(3),
        ]);

        // And one started in this browser just now.
        $this->post('/assessment/start');
        $current = Assessment::latest('id')->first();

        $this->post('/login', ['email' => 'lena@example.test', 'password' => 'password']);

        $this->assertSame($user->id, $earlier->fresh()->user_id, 'Matched on the address given.');
        $this->assertSame($user->id, $current->fresh()->user_id, 'Matched on the session, which login regenerates.');
    }

    public function test_the_reminder_goes_only_to_people_who_left_an_address_and_some_answers(): void
    {
        Mail::fake();

        $base = [
            'status' => 'in_progress', 'scores' => [],
            'started_at' => now()->subDays(2),
        ];
        $answered = [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]];

        $due       = Assessment::create($base + ['contact_email' => 'due@example.test',    'responses' => $answered]);
        $noAnswers = Assessment::create($base + ['contact_email' => 'bounce@example.test', 'responses' => []]);
        $noEmail   = Assessment::create($base + ['responses' => $answered]);
        $tooSoon   = Assessment::create(['status' => 'in_progress', 'scores' => [], 'started_at' => now()->subHour(),
                                         'contact_email' => 'soon@example.test', 'responses' => $answered]);
        $tooOld    = Assessment::create(['status' => 'in_progress', 'scores' => [], 'started_at' => now()->subDays(40),
                                         'contact_email' => 'old@example.test', 'responses' => $answered]);

        $this->artisan('assessments:remind')->assertSuccessful();

        Mail::assertSentCount(1);
        Mail::assertSent(AssessmentResume::class, fn ($mail) => $mail->hasTo('due@example.test')
            && $mail->reason === 'reminder');

        $this->assertNotNull($due->fresh()->reminder_sent_at);
        foreach ([$noAnswers, $noEmail, $tooSoon, $tooOld] as $spared) {
            $this->assertNull($spared->fresh()->reminder_sent_at);
        }

        // Older than the stale window, so it stops counting as live.
        $this->assertSame('abandoned', $tooOld->fresh()->status);
    }

    public function test_nobody_is_reminded_twice(): void
    {
        Mail::fake();

        Assessment::create([
            'status' => 'in_progress', 'scores' => [],
            'started_at' => now()->subDays(2),
            'contact_email' => 'due@example.test',
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);

        $this->artisan('assessments:remind');
        $this->artisan('assessments:remind');

        Mail::assertSentCount(1);
    }

    public function test_a_dry_run_sends_nothing_and_writes_nothing(): void
    {
        Mail::fake();

        $assessment = Assessment::create([
            'status' => 'in_progress', 'scores' => [],
            'started_at' => now()->subDays(2),
            'contact_email' => 'due@example.test',
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);

        // The wording matters as much as the behaviour: this is a command whose
        // whole purpose is to be read before anything is sent, so it must not
        // report in the past tense about things it did not do.
        // One assertion, not two: each expectsOutputToContain consumes a line,
        // and both phrases live on the same one.
        $this->artisan('assessments:remind', ['--dry-run' => true])
            ->expectsOutputToContain('Would send 1 reminder and mark 0 assessments abandoned. Nothing was sent or saved.')
            ->assertSuccessful();

        Mail::assertNothingSent();
        $this->assertNull($assessment->fresh()->reminder_sent_at);
    }

    public function test_the_reminder_carries_a_one_click_unsubscribe_header(): void
    {
        $assessment = Assessment::create([
            'status' => 'in_progress', 'scores' => [],
            'started_at' => now()->subDays(2),
            'contact_email' => 'due@example.test',
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);

        $headers = (new AssessmentResume($assessment, 'reminder'))->headers();
        $token   = $assessment->fresh()->unsubscribe_token;

        $this->assertNotNull($token, 'The header would point nowhere without a token.');

        // The pairing is what makes Gmail and Apple Mail show a real
        // unsubscribe control; List-Unsubscribe alone is widely ignored.
        $this->assertStringContainsString($token, $headers->text['List-Unsubscribe']);
        $this->assertStringContainsString('mailto:', $headers->text['List-Unsubscribe']);
        $this->assertSame('List-Unsubscribe=One-Click', $headers->text['List-Unsubscribe-Post']);

        // And in the body too, for the clients that surface neither.
        $this->assertStringContainsString(
            '/assessment/unsubscribe/' . $token,
            (new AssessmentResume($assessment, 'reminder'))->render()
        );
    }

    public function test_looking_at_the_unsubscribe_page_changes_nothing(): void
    {
        $assessment = Assessment::create([
            'status' => 'in_progress', 'scores' => [], 'started_at' => now(),
            'contact_email' => 'scan@example.test', 'responses' => [],
        ]);
        $token = $assessment->ensureUnsubscribeToken();

        // A mail scanner or link preview bot follows the URL with a GET. It
        // must not opt anybody out on their behalf.
        $this->get("/assessment/unsubscribe/{$token}")->assertOk();

        $this->assertNotNull($assessment->fresh(), 'A GET must not delete anything.');
        $this->assertNull($assessment->fresh()->reminders_opted_out_at);
    }

    public function test_one_click_unsubscribe_stops_the_reminder_and_removes_the_unfinished_assessment(): void
    {
        $assessment = Assessment::create([
            'status' => 'in_progress', 'scores' => [], 'started_at' => now()->subDays(2),
            'contact_email' => 'stop@example.test',
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);
        $token = $assessment->ensureUnsubscribeToken();

        // No CSRF token: the provider posts from outside any browser session.
        $this->post("/assessment/unsubscribe/{$token}", ['List-Unsubscribe' => 'One-Click'])
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

        $this->assertNull($assessment->fresh(), 'Someone who said stop should not be kept on file.');
    }

    public function test_unsubscribing_keeps_a_completed_assessment(): void
    {
        $assessment = Assessment::create([
            'status' => 'completed', 'scores' => [], 'started_at' => now()->subDay(),
            'completed_at' => now(), 'contact_email' => 'done@example.test', 'responses' => [],
        ]);
        $token = $assessment->ensureUnsubscribeToken();

        $this->post("/assessment/unsubscribe/{$token}", ['List-Unsubscribe' => 'One-Click'])->assertOk();

        // Deleting would take away results they already have and may want.
        $this->assertNotNull($assessment->fresh());
        $this->assertNotNull($assessment->fresh()->reminders_opted_out_at);
    }

    public function test_a_second_unsubscribe_is_not_an_error(): void
    {
        // Providers retry, and people click twice. Neither should see a failure.
        $this->post('/assessment/unsubscribe/never-existed', ['List-Unsubscribe' => 'One-Click'])
            ->assertOk();
    }

    public function test_nobody_who_opted_out_is_reminded(): void
    {
        Mail::fake();

        Assessment::create([
            'status' => 'in_progress', 'scores' => [], 'started_at' => now()->subDays(2),
            'contact_email' => 'optedout@example.test',
            'reminders_opted_out_at' => now(),
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);

        $this->artisan('assessments:remind')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_a_real_run_reports_what_it_actually_did(): void
    {
        Mail::fake();

        Assessment::create([
            'status' => 'in_progress', 'scores' => [],
            'started_at' => now()->subDays(2),
            'contact_email' => 'due@example.test',
            'responses' => [1 => ['question_id' => 1, 'answer_id' => 1, 'clusters' => ['T']]],
        ]);

        $this->artisan('assessments:remind')
            ->expectsOutputToContain('1 reminder sent')
            ->assertSuccessful();
    }
}
