<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Reading and exporting completed assessments.
 *
 * The dashboard counted assessments and the reports table listed them, but
 * every control that would let you do anything with one was a placeholder:
 * the detail link was href="#", the delete button was a confirm() dialog
 * telling you to contact support, and Export Data was a button with no form
 * behind it. The data was reachable only through the database.
 */
class AssessmentAdminController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        return view('admin.assessments.index', [
            'assessments' => Assessment::with(['user', 'results.pathway'])
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'status'          => $status,
            'totalCount'      => Assessment::count(),
            'completedCount'  => Assessment::where('status', 'completed')->count(),
        ]);
    }

    public function show(Assessment $assessment): View
    {
        $assessment->load(['user', 'results.pathway']);

        return view('admin.assessments.show', [
            'assessment' => $assessment,
            'answers'    => $this->readableAnswers($assessment),
            'clusters'   => $this->clusterScores($assessment),
        ]);
    }

    /**
     * One row per assessment. The answers are deliberately not flattened into
     * columns here: the question set changes over time, so a fixed set of
     * answer columns would stop lining up. The per-assessment page is where
     * the answers are read.
     */
    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status');

        $query = Assessment::with(['user', 'results.pathway'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->oldest();

        $filename = 'skillscoop-assessments-' . ($status ?: 'all') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'Started', 'Completed', 'Status', 'Name', 'Email',
                'Primary pathway', 'Score', 'Cluster', 'Other pathways', 'Questions answered',
            ]);

            $query->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $a) {
                    $primary = $a->results->firstWhere('result_type', 'primary') ?? $a->results->first();
                    $others  = $a->results->where('id', '!=', $primary?->id)
                        ->map(fn ($r) => $r->pathway?->name)->filter()->implode('; ');

                    fputcsv($out, [
                        $a->started_at?->format('Y-m-d H:i'),
                        $a->completed_at?->format('Y-m-d H:i'),
                        $a->status,
                        $a->user?->name ?? 'Anonymous',
                        $a->user?->email,
                        $primary?->pathway?->name,
                        $primary?->score,
                        $primary?->cluster,
                        $others,
                        is_array($a->responses) ? count($a->responses) : 0,
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $assessment->results()->delete();
        $assessment->delete();

        return redirect()
            ->route('admin.assessments.index')
            ->with('status', 'Assessment deleted.');
    }

    /**
     * Turn the stored responses into something readable.
     *
     * responses is a JSON map of question number to question_id, answer_id and
     * the clusters that answer scored, so the text of both has to be looked up.
     * Questions and answers can be edited or removed after someone has sat the
     * assessment, so anything missing is reported as such rather than dropped —
     * a gap in the record is worth seeing.
     */
    private function readableAnswers(Assessment $assessment): array
    {
        $responses = $assessment->responses ?? [];
        if (! $responses) {
            return [];
        }

        $questions = Question::whereIn('id', array_column($responses, 'question_id'))->get()->keyBy('id');
        $answers   = Answer::whereIn('id', array_column($responses, 'answer_id'))->get()->keyBy('id');

        $out = [];
        foreach ($responses as $number => $r) {
            $out[] = [
                'number'   => $number,
                'question' => $questions[$r['question_id'] ?? null]->question_text ?? 'Question no longer exists',
                'answer'   => $answers[$r['answer_id'] ?? null]->answer_text ?? 'Answer no longer exists',
                'clusters' => $r['clusters'] ?? [],
            ];
        }

        return $out;
    }

    /**
     * Cluster tallies with the labels used on the learner-facing results page,
     * so admin and learner are reading the same thing by the same name.
     */
    private function clusterScores(Assessment $assessment): array
    {
        $names = [
            'T' => 'Technical',
            'C' => 'Creative',
            'B' => 'Business',
            'S' => 'Security',
            'F' => 'Foundation',
        ];

        $scores = $assessment->scores ?? [];
        $max    = $scores ? max(array_map('intval', $scores)) : 0;

        $out = [];
        foreach ($names as $key => $label) {
            $value = (int) ($scores[$key] ?? 0);
            $out[] = [
                'key'     => $key,
                'label'   => $label,
                'score'   => $value,
                'percent' => $max > 0 ? round($value / $max * 100) : 0,
            ];
        }

        return $out;
    }
}
