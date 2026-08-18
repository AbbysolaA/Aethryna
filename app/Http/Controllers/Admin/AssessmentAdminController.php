<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
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
        $search = trim((string) $request->query('q'));

        return view('admin.assessments.index', [
            'assessments' => $this->filtered($status, $search)
                ->with(['user', 'results.pathway'])
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'status'         => $status,
            'search'         => $search,
            'totalCount'     => Assessment::count(),
            'completedCount' => Assessment::where('status', 'completed')->count(),
            'dropOff'        => $this->dropOff(),
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
        $search = trim((string) $request->query('q'));

        // Same filters as the screen: a download that quietly ignored the
        // search would not match what the person was looking at.
        $query = $this->filtered($status, $search)
            ->with(['user', 'results.pathway'])
            ->oldest();

        $filename = 'skillscoop-assessments-' . ($status ?: 'all') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'Started', 'Completed', 'Status', 'Name', 'Email', 'Has account',
                'Primary pathway', 'Score', 'Cluster', 'Other pathways',
                'Questions answered', 'Results emailed', 'Reminder sent',
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
                        $a->recipientName() ?? 'Anonymous',
                        $a->recipientEmail(),
                        $a->user_id ? 'yes' : 'no',
                        $primary?->pathway?->name,
                        $primary?->score,
                        $primary?->cluster,
                        $others,
                        $a->answeredCount(),
                        $a->results_emailed_at?->format('Y-m-d H:i'),
                        $a->reminder_sent_at?->format('Y-m-d H:i'),
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
     * The status and search filters, shared by the screen and the export.
     *
     * Search covers the address given on the assessment as well as the account
     * one. Most assessments have no account behind them, so searching only the
     * users table would miss nearly everybody.
     */
    private function filtered(?string $status, string $search): Builder
    {
        return Assessment::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->where(function ($outer) use ($search) {
                $outer
                    ->where('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            }));
    }

    /**
     * Where people stop.
     *
     * The headline "started vs completed" number hides the shape of the loss.
     * An assessment abandoned at question one is a bounce off the landing page;
     * one abandoned at question twelve is a problem with question twelve. They
     * need different fixes, so they are counted separately.
     *
     * Counted in PHP rather than SQL because the answer count lives inside a
     * JSON column and there is no portable way to measure it across SQLite and
     * MySQL. Only unfinished rows are loaded, and only their responses.
     */
    private function dropOff(): array
    {
        $totalQuestions = Question::active()->count();

        $bands = [
            'none'    => ['label' => 'Started, answered nothing', 'hint' => 'Clicked start and left', 'count' => 0],
            'early'   => ['label' => '1 to 4 answers',            'hint' => 'Left in the first stretch', 'count' => 0],
            'middle'  => ['label' => '5 to 9 answers',            'hint' => 'Left part way', 'count' => 0],
            'late'    => ['label' => '10 or more answers',        'hint' => 'Nearly finished and stopped', 'count' => 0],
        ];

        Assessment::query()
            ->unfinished()
            ->select(['id', 'responses', 'contact_email'])
            ->chunk(500, function ($rows) use (&$bands) {
                foreach ($rows as $row) {
                    $n = $row->answeredCount();

                    $key = match (true) {
                        $n === 0 => 'none',
                        $n < 5   => 'early',
                        $n < 10  => 'middle',
                        default  => 'late',
                    };

                    $bands[$key]['count']++;
                }
            });

        $unfinished = array_sum(array_column($bands, 'count'));

        foreach ($bands as $key => $band) {
            $bands[$key]['percent'] = $unfinished > 0
                ? round($band['count'] / $unfinished * 100)
                : 0;
        }

        return [
            'bands'          => $bands,
            'unfinished'     => $unfinished,
            'totalQuestions' => $totalQuestions,
            // The ones worth chasing: unfinished, and we know where to write.
            'reachable'      => Assessment::unfinished()->whereNotNull('contact_email')->count(),
            'reminded'       => Assessment::whereNotNull('reminder_sent_at')->count(),
        ];
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
