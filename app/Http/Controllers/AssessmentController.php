<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Pathway;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    /**
     * Show the assessment landing page
     */
    public function index()
    {
        return view('assessment.index');
    }

    /**
     * Start a new assessment
     */
    public function start(Request $request)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        // Check if user has an existing in-progress assessment
        $existingAssessment = Assessment::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('status', 'in_progress')->first();

        if ($existingAssessment) {
            $this->rememberAssessment($existingAssessment);

            return redirect()->route('assessment.question', ['question' => 1]);
        }

        // Create new assessment
        $assessment = Assessment::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'status' => 'in_progress',
            'started_at' => now(),
            'responses' => [],
            'scores' => [
                'T' => 0, // Technical
                'C' => 0, // Creative
                'B' => 0, // Business
                'S' => 0, // Security
                'F' => 0  // Foundation
            ]
        ]);

        $this->rememberAssessment($assessment);

        return redirect()->route('assessment.question', ['question' => 1]);
    }

    /**
     * Note which assessment this visitor is holding, in the session payload.
     *
     * Not the same thing as assessments.session_id. Signing in regenerates the
     * session ID, so that column stops matching at exactly the moment we want
     * to attach the assessment to the new account — but session *data* is
     * carried across the migration, so this key survives and the login listener
     * can still find it.
     */
    private function rememberAssessment(Assessment $assessment): void
    {
        Session::put('assessment_id', $assessment->id);
    }

    /**
     * Show a specific question
     */
    public function question(Request $request, $questionNumber)
    {
        $assessment = $this->getCurrentAssessment();

        if (!$assessment) {
            return redirect()->route('assessment.index');
        }

        // Guard: a finished assessment should not be answerable again. Without
        // this, revisiting /assessment/question/{n} after completing lets the
        // scores be mutated a second time.
        if ($assessment->status === 'completed') {
            return redirect()->route('assessment.results');
        }

        $question = Question::active()
            ->where('question_number', $questionNumber)
            ->with('answers')
            ->first();

        if (!$question) {
            return redirect()->route('assessment.results');
        }

        $totalQuestions = Question::active()->count();
        $progress = ($questionNumber / $totalQuestions) * 100;

        // The view offers to email a link back in, so it needs to know whether
        // we already hold an address — asking someone for it twice reads as if
        // the first time did not register.
        $savedEmail = $assessment->recipientEmail();

        return view('assessment.question', compact(
            'question', 'questionNumber', 'totalQuestions', 'progress', 'savedEmail'
        ));
    }

    /**
     * Process answer submission
     */
    public function answer(Request $request, $questionNumber)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);

        $assessment = $this->getCurrentAssessment();

        if (!$assessment) {
            return redirect()->route('assessment.index');
        }

        // Same guard as question(): never mutate scores on a finished assessment.
        if ($assessment->status === 'completed') {
            return redirect()->route('assessment.results');
        }

        $question = Question::active()
            ->where('question_number', $questionNumber)
            ->with('answers')
            ->first();

        if (!$question) {
            return redirect()->route('assessment.results');
        }

        $selectedAnswer = $question->answers->where('option_label', $request->answer)->first();

        if (!$selectedAnswer) {
            return back()->with('error', 'Invalid answer selected.');
        }

        // Update assessment responses
        $responses = $assessment->responses ?? [];
        $responses[$questionNumber] = [
            'question_id' => $question->id,
            'answer_id' => $selectedAnswer->id,
            'clusters' => $selectedAnswer->clusters
        ];

        // Update scores
        $scores = $assessment->scores ?? ['T' => 0, 'C' => 0, 'B' => 0, 'S' => 0, 'F' => 0];
        foreach ($selectedAnswer->clusters as $cluster) {
            if (isset($scores[$cluster])) {
                $scores[$cluster]++;
            }
        }

        $assessment->update([
            'responses' => $responses,
            'scores' => $scores,
            // Someone answering a question is not abandoned, whatever the tidy
            // in assessments:remind decided while they were away.
            'status' => 'in_progress',
        ]);

        // Move to next question or complete assessment
        $nextQuestionNumber = $questionNumber + 1;
        $totalQuestions = Question::active()->count();

        if ($nextQuestionNumber > $totalQuestions) {
            return $this->completeAssessment($assessment);
        }

        return redirect()->route('assessment.question', ['question' => $nextQuestionNumber]);
    }

    /**
     * Complete the assessment and calculate results
     */
    private function completeAssessment(Assessment $assessment)
    {
        $scores = $assessment->scores;

        // Calculate results based on scoring logic
        $results = $this->calculateResults($scores);

        // Save results
        foreach ($results as $result) {
            AssessmentResult::create([
                'assessment_id' => $assessment->id,
                'pathway_id' => $result['pathway_id'],
                'result_type' => $result['type'],
                'score' => $result['score'],
                'cluster' => $result['cluster'],
                'recommendation_text' => $result['recommendation']
            ]);
        }

        // Mark assessment as completed
        $assessment->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        $this->emailResults($assessment);

        return redirect()->route('assessment.results');
    }

    /**
     * Send the results, if we have anywhere to send them.
     *
     * Called both at completion and again if an address is given afterwards on
     * the results page, so results_emailed_at is what stops the same person
     * being sent the same results twice.
     *
     * Fail-soft throughout: a mail problem must never block seeing the results.
     */
    private function emailResults(Assessment $assessment): bool
    {
        $recipient = $assessment->recipientEmail();

        if (! $recipient || $assessment->results_emailed_at) {
            return false;
        }

        try {
            Mail::to($recipient)->send(new \App\Mail\AssessmentCompleted($assessment));
            $assessment->forceFill(['results_emailed_at' => now()])->save();

            return true;
        } catch (\Throwable $e) {
            Log::warning('Assessment results email failed', [
                'assessment_id' => $assessment->id,
                'error'         => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Take an address on the results page and send the results there.
     *
     * The assessment is deliberately not gated behind an email at the start:
     * the landing page promises it is not a school-style test, and a sign-up
     * wall before question one turns away exactly the people this exists for.
     * Asking here instead means the ask lands when the person already has
     * something they want — their result — rather than before they know
     * whether it is worth anything.
     */
    public function storeContact(Request $request)
    {
        $assessment = $this->getCurrentAssessment();

        if (! $assessment || $assessment->status !== 'completed') {
            return redirect()->route('assessment.index');
        }

        $data = $request->validate([
            'contact_name'  => ['nullable', 'string', 'max:120'],
            'contact_email' => ['required', 'email:rfc', 'max:255'],
        ], [], [
            'contact_email' => 'email address',
        ]);

        $assessment->update([
            'contact_name'  => ($data['contact_name'] ?? null) ?: $assessment->contact_name,
            'contact_email' => $data['contact_email'],
        ]);

        $sent = $this->emailResults($assessment);

        return redirect()
            ->route('assessment.results')
            ->with('status', $sent
                ? 'Sent. Your results are on their way to ' . $data['contact_email'] . '.'
                : 'Saved. We could not send the email just now, but your results stay on this page.');
    }

    /**
     * Save an address part way through and email a link back in.
     *
     * The whole point is that people stop: they are on a phone, on a bus, on
     * someone else's laptop. Without this the only way back is the same browser
     * before the session expires, which is why so many starts never finish.
     */
    public function saveProgress(Request $request)
    {
        $assessment = $this->getCurrentAssessment();

        if (! $assessment) {
            return redirect()->route('assessment.index');
        }

        if ($assessment->status === 'completed') {
            return redirect()->route('assessment.results');
        }

        $data = $request->validate([
            'contact_name'  => ['nullable', 'string', 'max:120'],
            'contact_email' => ['required', 'email:rfc', 'max:255'],
        ], [], [
            'contact_email' => 'email address',
        ]);

        $assessment->update([
            'contact_name'  => ($data['contact_name'] ?? null) ?: $assessment->contact_name,
            'contact_email' => $data['contact_email'],
        ]);

        // Minted here rather than left to the mailable to create while it
        // renders: if the send is queued or fails, the token still has to
        // exist, or the reminder later has no link to offer.
        $assessment->ensureResumeToken();

        try {
            Mail::to($data['contact_email'])
                ->send(new \App\Mail\AssessmentResume($assessment, 'saved'));

            $message = 'Sent. Check ' . $data['contact_email'] . ' for the link back in.';
        } catch (\Throwable $e) {
            Log::warning('Assessment resume email failed', [
                'assessment_id' => $assessment->id,
                'error'         => $e->getMessage(),
            ]);

            // The address is saved either way, so the reminder run can still
            // reach them. Say what is true rather than claiming a send.
            $message = 'Saved your place. The email did not go out just now — we will try again shortly.';
        }

        return back()->with('status', $message);
    }

    /**
     * Open an assessment from a link in an email.
     *
     * The token is the only credential, so it is treated as one: unguessable,
     * scoped to a single assessment, and it grants nothing beyond continuing
     * that assessment. A finished one sends you to its results instead.
     */
    public function resume(string $token)
    {
        $assessment = Assessment::where('resume_token', $token)->first();

        if (! $assessment) {
            return redirect()
                ->route('assessment.index')
                ->with('error', 'That link has expired or was never valid. You can start again below — it takes about two minutes.');
        }

        // Bind this browser to the assessment. Any other unfinished assessment
        // sitting on this session is detached first, otherwise getCurrentAssessment()
        // could pick the wrong one and the link would silently do nothing. A
        // detached row is still reachable by its own resume link.
        Assessment::where('session_id', Session::getId())
            ->where('id', '!=', $assessment->id)
            ->unfinished()
            ->update(['session_id' => null]);

        $assessment->forceFill([
            'session_id' => Session::getId(),
            'status'     => $assessment->status === 'completed' ? 'completed' : 'in_progress',
        ])->save();

        $this->rememberAssessment($assessment);

        if ($assessment->status === 'completed') {
            return redirect()->route('assessment.results');
        }

        $next = $this->nextQuestionNumber($assessment);

        // Every active question already answered but never scored — the browser
        // died between the last answer and the redirect. Finish it rather than
        // sending them back into a question they have already done.
        if ($next === null) {
            return $this->completeAssessment($assessment);
        }

        return redirect()
            ->route('assessment.question', ['question' => $next])
            ->with('status', 'Welcome back. Your answers are where you left them.');
    }

    /**
     * The first active question this assessment has no answer for, or null if
     * there is none left.
     *
     * Not simply count($responses) + 1: questions can be deactivated or
     * renumbered after someone starts, and resuming onto a question they have
     * already answered would score its clusters a second time.
     */
    private function nextQuestionNumber(Assessment $assessment): ?int
    {
        $answered = array_map('intval', array_keys($assessment->responses ?? []));

        $numbers = Question::active()
            ->orderBy('question_number')
            ->pluck('question_number');

        foreach ($numbers as $number) {
            if (! in_array((int) $number, $answered, true)) {
                return (int) $number;
            }
        }

        return null;
    }

    /**
     * Calculate pathway recommendations based on scores
     */
    private function calculateResults($scores)
    {
        // Sort scores in descending order
        arsort($scores);
        $sortedClusters = array_keys($scores);

        $results = [];

        // Primary result
        $primaryCluster = $sortedClusters[0];
        $primaryPathways = $this->getPathwaysForCluster($primaryCluster);

        if (!empty($primaryPathways)) {
            $results[] = [
                'pathway_id' => $primaryPathways[0]['id'],
                'type' => 'primary',
                'score' => $scores[$primaryCluster],
                'cluster' => $primaryCluster,
                'recommendation' => $this->getRecommendationText($primaryCluster, 'primary')
            ];
        }

        // Secondary result (if different from primary)
        $secondaryCluster = $sortedClusters[1] ?? null;
        if ($secondaryCluster && $secondaryCluster !== $primaryCluster) {
            $secondaryPathways = $this->getPathwaysForCluster($secondaryCluster);

            if (!empty($secondaryPathways)) {
                $results[] = [
                    'pathway_id' => $secondaryPathways[0]['id'],
                    'type' => 'secondary',
                    'score' => $scores[$secondaryCluster],
                    'cluster' => $secondaryCluster,
                    'recommendation' => $this->getRecommendationText($secondaryCluster, 'secondary')
                ];
            }
        }

        return $results;
    }

    /**
     * Get pathways for a specific cluster
     */
    private function getPathwaysForCluster($cluster)
    {
        $clusterMap = [
            'T' => 'technical',
            'C' => 'creative',
            'B' => 'business',
            'S' => 'security',
            'F' => 'foundation'
        ];

        return Pathway::active()
            ->where('category', $clusterMap[$cluster] ?? 'foundation')
            ->get()
            ->toArray();
    }

    /**
     * Get recommendation text for cluster and type
     */
    private function getRecommendationText($cluster, $type)
    {
        $texts = [
            'T' => [
                'primary' => 'You enjoy solving problems and figuring out how things work. You\'re motivated by building tools and systems people rely on.',
                'secondary' => 'You also show interest in technical problem-solving and system building.'
            ],
            'C' => [
                'primary' => 'You care about how things look, feel, and connect with people. You\'re drawn to visuals, experiences, and stories.',
                'secondary' => 'You also show creative and design-oriented thinking.'
            ],
            'B' => [
                'primary' => 'You\'re a natural organiser, planner, or communicator. You enjoy bringing order to chaos and helping people work better together.',
                'secondary' => 'You also show strong organisational and planning skills.'
            ],
            'S' => [
                'primary' => 'You notice details, think in risks and what ifs, and like understanding how systems work under the surface.',
                'secondary' => 'You also show analytical thinking and attention to system details.'
            ],
            'F' => [
                'primary' => 'You\'re developing your digital confidence. This is where your rise begins.',
                'secondary' => 'Consider starting with foundational digital skills.'
            ]
        ];

        return $texts[$cluster][$type] ?? 'Consider exploring this pathway to discover your strengths.';
    }

    /**
     * Show assessment results
     */
    public function results()
    {
        $assessment = $this->getCurrentAssessment();

        if (!$assessment || $assessment->status !== 'completed') {
            return redirect()->route('assessment.index');
        }

        $results = $assessment->results()->with('pathway')->get();
        $scores = $assessment->scores;

        // Drives the "where should we send this?" card: shown only when we have
        // no address, and replaced by a confirmation once we do.
        $savedEmail = $assessment->recipientEmail();

        return view('assessment.results', compact('results', 'scores', 'savedEmail'));
    }

    /**
     * Get current assessment for user/session
     */
    private function getCurrentAssessment()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        // The assessment this browser is actually working on, noted in the
        // session payload when it was started or resumed.
        //
        // Checked before the session_id column because the two part company:
        // signing in regenerates the session ID, so somebody who logs in half
        // way through would otherwise look like a stranger and lose their
        // answers. Session data survives that regeneration; the ID does not.
        if ($assessmentId = Session::get('assessment_id')) {
            $assessment = Assessment::find($assessmentId);

            // Not if it belongs to somebody else. On a shared machine the
            // session key can outlive the person who set it.
            if ($assessment && (! $assessment->user_id || $assessment->user_id === $userId)) {
                return $assessment;
            }
        }

        return Assessment::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->latest()->first();
    }

    /**
     * Reset assessment (for testing)
     */
    public function reset()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        Assessment::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        Session::forget('assessment_id');

        return redirect()->route('assessment.index');
    }
}
