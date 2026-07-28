<?php

namespace App\Http\Controllers;

use App\Mail\VolunteerWelcome;
use App\Models\User;
use App\Models\VolunteerDocument;
use App\Models\VolunteerEngagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The volunteer's own area: claiming an offer, answering it, and logging hours.
 *
 * Mentors come through here too. A mentor is a volunteer role that additionally
 * grants the /mentor area on acceptance, rather than a separate pipeline.
 */
class VolunteerController extends Controller
{
    /**
     * Landing page for the link in the offer email. Public by design.
     *
     * A volunteer may have applied through a partner organisation or been
     * approached at a panel, so there is no guarantee they hold an account.
     * Guests are shown the offer and given both doors, sign in and register,
     * and come back here once authenticated to claim it.
     */
    public function claim(string $token): View|RedirectResponse|Response
    {
        $engagement = VolunteerEngagement::claimable()
            ->where('offer_token', $token)
            ->with('role')
            ->first();

        if (! $engagement) {
            // Covers wrong, expired and already-answered tokens with one
            // response, so this page cannot be used to probe which is which.
            return response()->view('volunteer.offer-unavailable', [], 404);
        }

        if (! Auth::check()) {
            session(['url.intended' => route('volunteer.claim', $token)]);
            session(['claiming_volunteer_offer' => true]);

            // Whether the address the offer went to already has an account
            // decides which door we show. Someone new sets a password here and
            // never sees the cohort application; someone existing signs in.
            return view('volunteer.claim', [
                'engagement'    => $engagement,
                'accountExists' => User::where('email', $engagement->offer_email)->exists(),
            ]);
        }

        // An offer pre-bound to one account, opened by someone signed in as
        // another, would otherwise no-op through claimFor() and dead-end on a
        // bare 403 from show(). Same page as an expired link, which is honest:
        // this link is not usable by this account.
        if ($engagement->user_id !== null && $engagement->user_id !== Auth::id()) {
            return response()->view('volunteer.offer-unavailable', [], 403);
        }

        // Signed in as somebody other than the addressee. Binding silently is
        // how an offer to abby@skillscoop.org ended up attached to an admin
        // account, with the onboarding pack emailed to the wrong inbox. Ask.
        if ($engagement->user_id === null && ! $this->addressMatches($engagement, Auth::user())) {
            return view('volunteer.claim-mismatch', [
                'engagement'  => $engagement,
                'signedInAs'  => Auth::user(),
                'token'       => $token,
            ]);
        }

        $engagement->claimFor(Auth::user());

        session()->forget('claiming_volunteer_offer');

        return redirect()->route('volunteer.show', $engagement);
    }

    /**
     * Create the account for an offer and sign them straight in.
     *
     * The volunteer never sees the cohort application. They hold a token that
     * proves they received the offer email, so the address is taken from the
     * engagement rather than typed, and they only choose a password.
     *
     * Refuses outright if that address already has an account. Allowing a
     * password to be set on an existing account would turn a forwarded offer
     * link into account takeover. Those people sign in instead.
     */
    public function claimStore(Request $request, string $token): RedirectResponse
    {
        $engagement = VolunteerEngagement::claimable()
            ->where('offer_token', $token)
            ->with('role')
            ->firstOr(fn () => abort(404));

        if (User::where('email', $engagement->offer_email)->exists()) {
            return redirect()
                ->route('volunteer.claim', $token)
                ->with('error', 'That address already has an account. Please sign in instead.');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $engagement->offer_email,
            'password'          => Hash::make($validated['password']),
            'role'              => 'learner',
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $engagement->claimFor($user);

        session()->forget('claiming_volunteer_offer');

        return redirect()->route('volunteer.show', $engagement);
    }

    /**
     * Confirm claiming an offer under an account whose address does not match
     * the one it was sent to. Deliberate and explicit, never silent.
     */
    public function claimAs(string $token): RedirectResponse
    {
        $engagement = VolunteerEngagement::claimable()
            ->where('offer_token', $token)
            ->firstOr(fn () => abort(404));

        abort_unless($engagement->user_id === null, 403);

        $engagement->claimFor(Auth::user());

        session()->forget('claiming_volunteer_offer');

        return redirect()->route('volunteer.show', $engagement);
    }

    /**
     * Everything this person has volunteered for, past and present.
     */
    public function index(): View
    {
        return view('volunteer.index', [
            'engagements' => Auth::user()
                ->volunteerEngagements()
                ->with('role')
                ->withSum('hours', 'hours')
                ->get(),
        ]);
    }

    public function show(VolunteerEngagement $engagement): View
    {
        $this->authoriseOwner($engagement);

        $engagement->load('role', 'hours');

        return view('volunteer.show', [
            'engagement' => $engagement,
            'timeline'   => $engagement->timeline(),
            'hours'      => $engagement->hours->sortByDesc('worked_on'),
            'totalHours' => $engagement->totalHours(),
        ]);
    }

    /**
     * Accept or decline. Only valid while the offer is still open, so a stale
     * tab cannot resurrect an answered or withdrawn offer.
     */
    public function respond(Request $request, VolunteerEngagement $engagement): RedirectResponse
    {
        $this->authoriseOwner($engagement);

        $validated = $request->validate([
            'decision' => ['required', 'in:accept,decline'],
        ]);

        if (! $engagement->offerIsOpen()) {
            return redirect()
                ->route('volunteer.show', $engagement)
                ->with('error', 'This offer is no longer open.');
        }

        if ($validated['decision'] === 'decline') {
            $engagement->decline();

            return redirect()
                ->route('volunteer.show', $engagement)
                ->with('status', 'You have declined this offer. Thank you for letting us know.');
        }

        $engagement->accept();
        $this->grantAccess($engagement);
        $this->sendWelcome($engagement);

        return redirect()
            ->route('volunteer.show', $engagement)
            ->with('status', 'Offer accepted. Your onboarding pack is on its way by email.');
    }

    /**
     * Log a block of time. Write-once: the form states the entry cannot be
     * changed afterwards, and nothing here offers an update path.
     */
    public function storeHours(Request $request, VolunteerEngagement $engagement): RedirectResponse
    {
        $this->authoriseOwner($engagement);

        if (! $engagement->wasAccepted()) {
            return redirect()
                ->route('volunteer.show', $engagement)
                ->with('error', 'You can log hours once you have accepted the offer.');
        }

        $validated = $request->validate([
            'worked_on' => ['required', 'date', 'before_or_equal:today'],
            'hours'     => ['required', 'numeric', 'min:0.25', 'max:24'],
            'note'      => ['nullable', 'string', 'max:255'],
            'confirmed' => ['accepted'],
        ], [
            'worked_on.before_or_equal' => 'You cannot log hours for a date in the future.',
            'confirmed.accepted'        => 'Please confirm the hours are correct before recording them.',
        ]);

        $engagement->hours()->create([
            'worked_on' => $validated['worked_on'],
            'hours'     => $validated['hours'],
            'note'      => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('volunteer.show', $engagement)
            ->with('status', 'Hours recorded.');
    }

    /**
     * Serve a file from the onboarding pack.
     *
     * The files sit on a disk that is not web-reachable, so this is the only
     * way out. Restricted to volunteers and admins rather than any signed-in
     * account, because the handover brief and access checklist describe
     * internal state and learners have no business reading them.
     */
    public function downloadDocument(VolunteerDocument $document): StreamedResponse
    {
        $user = Auth::user();

        abort_unless($user->isVolunteer() || $user->isAdmin(), 403);

        // A deactivated document stays reachable for an admin checking it, but
        // 404s for everyone else so an old link stops working.
        abort_unless($document->is_active || $user->isAdmin(), 404);

        // The row can outlive the file if storage was cleared underneath it.
        abort_unless($document->exists(), 404);

        return Storage::disk(VolunteerDocument::DISK)
            ->download($document->path, $document->original_name);
    }

    // --- Internals ---

    /**
     * Whether the signed-in account is the one the offer was addressed to.
     * Compared case-insensitively; addresses are stored as typed.
     */
    private function addressMatches(VolunteerEngagement $engagement, User $user): bool
    {
        return strcasecmp($engagement->offer_email, $user->email) === 0;
    }

    /**
     * An engagement is readable only by the account that claimed it. Admins
     * use the admin roster rather than this area, so there is no bypass here.
     */
    private function authoriseOwner(VolunteerEngagement $engagement): void
    {
        abort_unless($engagement->user_id === Auth::id(), 403);
    }

    /**
     * Raise the account to the access level the role confers.
     *
     * Only ever raises. Someone who is already a coach or admin and picks up a
     * volunteer stint keeps their existing access, because demoting an admin
     * to 'volunteer' would lock them out of their own dashboard.
     */
    private function grantAccess(VolunteerEngagement $engagement): void
    {
        $user   = $engagement->user;
        $grants = $engagement->role?->grants_access;

        if (! $user || ! $grants) {
            return;
        }

        if (in_array($user->role, ['coach', 'admin'], true)) {
            return;
        }

        // A mentor picking up a second, non-mentor role keeps mentor access.
        if ($user->role === 'mentor' && $grants !== 'mentor') {
            return;
        }

        $user->forceFill(['role' => $grants])->save();
    }

    /**
     * Send the onboarding pack.
     *
     * Failures are swallowed and logged on purpose. accept() has already
     * committed by this point, so letting a mail problem bubble would show the
     * volunteer a 500 on an acceptance that actually succeeded, and leave them
     * unable to decline because the offer is no longer open. The email is
     * recoverable by hand; a confused volunteer staring at an error is not.
     */
    private function sendWelcome(VolunteerEngagement $engagement): void
    {
        try {
            Mail::to($engagement->user?->email ?? $engagement->offer_email)
                ->send(new VolunteerWelcome(
                    firstName: str($engagement->user?->name ?? $engagement->offer_name)->before(' ')->toString(),
                    role: $engagement->role->title,
                    engagementUrl: route('volunteer.show', $engagement),
                ));
        } catch (\Throwable $e) {
            Log::error('Volunteer welcome email failed to send.', [
                'engagement_id' => $engagement->id,
                'email'         => $engagement->user?->email ?? $engagement->offer_email,
                'error'         => $e->getMessage(),
            ]);
        }
    }
}
