<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Pathway;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return redirect()->route('assessment.question', ['question' => 1]);
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

        return view('assessment.question', compact('question', 'questionNumber', 'totalQuestions', 'progress'));
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
            'scores' => $scores
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

        // Email the results so the learner has them outside the session.
        // Fail-soft: a mail problem must never block seeing the results page.
        $recipient = $assessment->user?->email ?? auth()->user()?->email;
        if ($recipient) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipient)
                    ->send(new \App\Mail\AssessmentCompleted($assessment));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Assessment results email failed', [
                    'assessment_id' => $assessment->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('assessment.results');
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

        return view('assessment.results', compact('results', 'scores'));
    }

    /**
     * Get current assessment for user/session
     */
    private function getCurrentAssessment()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

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

        return redirect()->route('assessment.index');
    }
}
