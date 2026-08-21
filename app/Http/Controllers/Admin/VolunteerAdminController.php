<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VolunteerOffer;
use App\Models\User;
use App\Models\VolunteerEngagement;
use App\Models\VolunteerRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Admin side of volunteering: extending offers and tracking onboarding.
 *
 * Mentors are handled here too. Offering the Mentor role grants the /mentor
 * area on acceptance, so there is no separate mentor recruitment screen.
 */
class VolunteerAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.volunteers.index', [
            // Applications first: they are the rows waiting on a decision, and
            // ordering by offer_extended_at alone would sink them to the
            // bottom because an application has no offer date yet.
            'engagements' => VolunteerEngagement::with('role', 'user')
                ->withSum('hours', 'hours')
                ->orderByRaw("CASE WHEN status = 'applied' THEN 0 ELSE 1 END")
                ->latest('created_at')
                ->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.volunteers.create', [
            'roles' => VolunteerRole::where('is_open', true)->orderBy('title')->get(),
        ]);
    }

    /**
     * Extend an offer and email it.
     *
     * The offer is addressed to an email rather than a user, because most
     * volunteers have no account at this point. If one already exists on that
     * address it is attached now as a convenience; otherwise the engagement
     * binds when they claim the link.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'volunteer_role_id' => ['required', 'exists:volunteer_roles,id'],
            'offer_name'        => ['required', 'string', 'max:255'],
            'offer_email'       => ['required', 'email', 'max:255'],
            'starts_on'         => ['required', 'date'],
            'ends_on'           => ['nullable', 'date', 'after:starts_on'],
            'response_days'     => ['nullable', 'integer', 'min:1', 'max:90'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ], [
            'ends_on.after' => 'The end date has to be after the start date.',
        ]);

        $role = VolunteerRole::findOrFail($validated['volunteer_role_id']);

        // Refuse a second live offer for the same role and address, otherwise
        // two valid tokens exist and whichever is answered second overwrites
        // the first silently.
        $duplicate = VolunteerEngagement::claimable()
            ->where('offer_email', $validated['offer_email'])
            ->where('volunteer_role_id', $role->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->with('error', 'There is already an open offer for that person and role.');
        }

        $engagement = VolunteerEngagement::create([
            'volunteer_role_id' => $role->id,
            'user_id'           => User::where('email', $validated['offer_email'])->value('id'),
            'offer_name'        => $validated['offer_name'],
            'offer_email'       => $validated['offer_email'],
            'starts_on'         => $validated['starts_on'],
            'ends_on'           => $validated['ends_on'] ?? null,
            'notes'             => $validated['notes'] ?? null,
        ]);

        $engagement->extendOffer($validated['response_days'] ?? null);
        [$key, $message] = $this->offerFlash($engagement, $this->sendOffer($engagement));

        return redirect()->route('admin.volunteers.index')->with($key, $message);
    }

    /**
     * An applicant's CV.
     *
     * The file is on a disk the web server does not serve, so this is the only
     * route to it and it sits inside the admin group. Served as a download
     * rather than inline: these are unsolicited documents from strangers, and
     * nothing is gained by asking the browser to render one.
     */
    public function downloadCv(VolunteerEngagement $engagement): StreamedResponse
    {
        // The row can outlive the file if storage was cleared underneath it.
        abort_unless($engagement->hasCv(), 404);

        return Storage::disk(VolunteerEngagement::CV_DISK)->download(
            $engagement->cv_path,
            $engagement->cv_original_name ?: 'cv'
        );
    }

    /**
     * Review an application and set the dates before the offer goes out.
     */
    public function extendForm(VolunteerEngagement $engagement): View|RedirectResponse
    {
        if ($engagement->status !== 'applied') {
            return redirect()
                ->route('admin.volunteers.index')
                ->with('error', 'That engagement is not an open application.');
        }

        return view('admin.volunteers.extend', [
            'engagement' => $engagement->load('role'),
        ]);
    }

    /**
     * Turn an application into an offer. The engagement already exists, so
     * this only fills in the dates, mints the token and sends the email.
     */
    public function extend(Request $request, VolunteerEngagement $engagement): RedirectResponse
    {
        if ($engagement->status !== 'applied') {
            return redirect()
                ->route('admin.volunteers.index')
                ->with('error', 'That engagement is not an open application.');
        }

        $validated = $request->validate([
            'starts_on'     => ['required', 'date'],
            'ends_on'       => ['nullable', 'date', 'after:starts_on'],
            'response_days' => ['nullable', 'integer', 'min:1', 'max:90'],
        ], [
            'ends_on.after' => 'The end date has to be after the start date.',
        ]);

        $engagement->forceFill([
            'starts_on' => $validated['starts_on'],
            'ends_on'   => $validated['ends_on'] ?? null,
        ])->save();

        $engagement->extendOffer($validated['response_days'] ?? null);
        [$key, $message] = $this->offerFlash($engagement, $this->sendOffer($engagement));

        return redirect()->route('admin.volunteers.index')->with($key, $message);
    }

    /**
     * Remove an engagement entirely.
     *
     * For a mis-sent offer, a withdrawn one, or test data. Logged hours go
     * with it through the foreign key cascade, which is why the confirmation
     * says so rather than leaving it as a surprise.
     *
     * Access is revoked if this was the person's last live engagement, so
     * deleting the only reason someone held volunteer access does not leave
     * them holding it.
     */
    public function destroy(VolunteerEngagement $engagement): RedirectResponse
    {
        $user = $engagement->user;
        $name = $engagement->user?->name ?? $engagement->offer_name;

        $engagement->delete();

        $this->revokeAccessIfLastEngagement($user);

        return redirect()
            ->route('admin.volunteers.index')
            ->with('status', 'Removed the engagement for ' . $name . '.');
    }

    /**
     * Drop a volunteer or mentor back to learner once they hold no accepted
     * engagements at all.
     *
     * Coaches and admins are left alone: their access does not come from
     * volunteering, and demoting an admin here would lock them out of the very
     * screen they just used.
     */
    private function revokeAccessIfLastEngagement(?User $user): void
    {
        if (! $user || ! in_array($user->role, ['volunteer', 'mentor'], true)) {
            return;
        }

        $stillEngaged = VolunteerEngagement::where('user_id', $user->id)
            ->whereIn('status', ['offer_extended', 'offer_accepted'])
            ->exists();

        if (! $stillEngaged) {
            $user->forceFill(['role' => 'learner'])->save();
        }
    }

    /**
     * Email the offer. Shared by the direct-offer and from-application paths
     * so the two cannot drift.
     *
     * The offer row is already saved by the time this runs, so a mail failure
     * must not throw: that would show a 500 for an offer that exists. It is
     * reported instead, because an admin needs to know to chase it by hand.
     *
     * @return bool Whether the message was handed to the mailer.
     */
    private function sendOffer(VolunteerEngagement $engagement): bool
    {
        try {
            Mail::to($engagement->offer_email)->send(new VolunteerOffer(
                firstName:  str($engagement->offer_name)->before(' ')->toString(),
                role:       $engagement->role->title,
                startsOn:   $engagement->starts_on,
                endsOn:     $engagement->ends_on ?? $engagement->starts_on->copy()->addYear(),
                respondUrl: route('volunteer.claim', $engagement->offer_token),
                respondBy:  $engagement->offer_expires_at,
            ));

            return true;
        } catch (\Throwable $e) {
            Log::error('Volunteer offer email failed to send.', [
                'engagement_id' => $engagement->id,
                'email'         => $engagement->offer_email,
                'error'         => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Flash wording that tells the truth about whether the email went out.
     */
    private function offerFlash(VolunteerEngagement $engagement, bool $sent): array
    {
        return $sent
            ? ['status', 'Offer sent to ' . $engagement->offer_email . '.']
            : ['error', 'The offer was created but the email could not be sent. Check the mail log and send the link by hand.'];
    }

    /**
     * Record onboarding returns and completion. Each field is a timestamp that
     * is set once; unchecking clears it, so a mistake is recoverable.
     */
    public function update(Request $request, VolunteerEngagement $engagement): RedirectResponse
    {
        $request->validate([
            'agreement_signed' => ['nullable', 'boolean'],
            'nda_signed'       => ['nullable', 'boolean'],
            'dbs_cleared'      => ['nullable', 'boolean'],
            'mark_complete'    => ['nullable', 'boolean'],
        ]);

        $engagement->forceFill([
            'agreement_signed_at' => $request->boolean('agreement_signed') ? ($engagement->agreement_signed_at ?? now()) : null,
            'nda_signed_at'       => $request->boolean('nda_signed') ? ($engagement->nda_signed_at ?? now()) : null,
            'dbs_cleared_at'      => $request->boolean('dbs_cleared') ? ($engagement->dbs_cleared_at ?? now()) : null,
        ])->save();

        if ($request->boolean('mark_complete') && $engagement->wasAccepted()) {
            $engagement->forceFill([
                'status'       => 'complete',
                'completed_at' => $engagement->completed_at ?? now(),
            ])->save();
        }

        return back()->with('status', 'Engagement updated.');
    }
}
