<?php

namespace App\Http\Controllers;

use App\Mail\SafeguardingConcernRaised;
use App\Models\SafeguardingConcern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Raising a safeguarding concern.
 *
 * Deliberately separate from the coach at-risk flag. A coach flagging a
 * learner writes to that coach's own cohort record; a concern raised here is
 * an escalation to the safeguarding lead, who decides what happens next.
 * Mentors, coaches and admins can all raise one, because anyone who notices
 * something should be able to say so.
 *
 * The record is written to the database FIRST and the email is sent second,
 * fail-soft. A safeguarding concern must never be lost because a mail server
 * was unreachable, and an email inbox is not an auditable record.
 */
class SafeguardingController extends Controller
{
    /**
     * Anyone in a supporting role may raise a concern.
     */
    protected function authoriseRaiser(): User
    {
        $user = auth()->user();

        abort_unless(
            $user && ($user->isMentor() || $user->isCoach() || $user->isAdmin()),
            403,
            'Only mentors, coaches and administrators can raise a safeguarding concern.'
        );

        return $user;
    }

    public function create(User $learner)
    {
        $this->authoriseRaiser();

        return view('safeguarding.create', compact('learner'));
    }

    public function store(Request $request, User $learner)
    {
        $raiser = $this->authoriseRaiser();

        $validated = $request->validate([
            'concern' => ['required', 'string', 'min:20', 'max:5000'],
            'urgency' => ['required', 'in:routine,urgent'],
        ]);

        // Write the record first. This is the system of record.
        $concern = SafeguardingConcern::create([
            'raised_by_user_id' => $raiser->id,
            'learner_id'        => $learner->id,
            'raised_by_role'    => $raiser->role,
            'concern'           => $validated['concern'],
            'urgency'           => $validated['urgency'],
            'status'            => 'new',
        ]);

        // Then notify the safeguarding lead. Fail-soft: if mail is down the
        // concern still exists and can be picked up from the record.
        $inbox = config('mail.safeguarding_inbox') ?: 'hello@skillscoop.org';

        try {
            Mail::to($inbox)->send(new SafeguardingConcernRaised($concern));
        } catch (\Throwable $e) {
            // Log without the concern body, which contains personal data.
            Log::error('Safeguarding concern saved but notification email failed', [
                'concern_id' => $concern->id,
                'urgency'    => $concern->urgency,
                'error'      => $e->getMessage(),
            ]);

            return redirect()
                ->route('safeguarding.create', $learner)
                ->with('warning', 'Your concern has been recorded (reference SC-' . $concern->id . '), but the notification email did not send. Please contact the safeguarding lead directly so it is not missed.');
        }

        return redirect()
            ->route('safeguarding.create', $learner)
            ->with('success', 'Concern recorded and sent to the safeguarding lead for review. Reference SC-' . $concern->id . '.');
    }

    // ── Review screen (safeguarding lead) ────────────────────────────────────

    /**
     * Open concerns first, urgent before routine, oldest urgent at the top so
     * nothing quietly ages out of sight.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'open');

        $query = SafeguardingConcern::with(['learner', 'raisedBy', 'reviewedBy']);

        if ($status === 'open') {
            $query->whereIn('status', ['new', 'acknowledged']);
        } elseif (in_array($status, ['new', 'acknowledged', 'actioned', 'closed'], true)) {
            $query->where('status', $status);
        }

        $concerns = $query
            ->orderByRaw("CASE WHEN urgency = 'urgent' THEN 0 ELSE 1 END")
            ->orderBy('created_at')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'open'         => SafeguardingConcern::whereIn('status', ['new', 'acknowledged'])->count(),
            'new'          => SafeguardingConcern::where('status', 'new')->count(),
            'acknowledged' => SafeguardingConcern::where('status', 'acknowledged')->count(),
            'actioned'     => SafeguardingConcern::where('status', 'actioned')->count(),
            'closed'       => SafeguardingConcern::where('status', 'closed')->count(),
            'all'          => SafeguardingConcern::count(),
        ];

        return view('admin.safeguarding.index', compact('concerns', 'status', 'counts'));
    }

    public function show(SafeguardingConcern $concern)
    {
        $concern->load(['learner', 'raisedBy', 'reviewedBy']);

        return view('admin.safeguarding.show', compact('concern'));
    }

    /**
     * Record the lead's decision. Every update stamps who reviewed it and
     * when, so the trail is complete without a separate audit table.
     */
    public function update(Request $request, SafeguardingConcern $concern)
    {
        $validated = $request->validate([
            'status'       => ['required', 'in:new,acknowledged,actioned,closed'],
            'review_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $concern->update([
            'status'              => $validated['status'],
            'review_notes'        => $validated['review_notes'] ?? $concern->review_notes,
            'reviewed_by_user_id' => auth()->id(),
            'reviewed_at'         => now(),
        ]);

        return redirect()
            ->route('admin.safeguarding.show', $concern)
            ->with('success', 'Decision recorded against SC-' . $concern->id . '.');
    }
}
