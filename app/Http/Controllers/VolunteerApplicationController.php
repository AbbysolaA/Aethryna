<?php

namespace App\Http\Controllers;

use App\Mail\VolunteerApplicationReceived;
use App\Models\User;
use App\Models\VolunteerEngagement;
use App\Models\VolunteerRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * The public front door: someone puts themselves forward for an open role.
 *
 * An application lands as an engagement with status 'applied' and no offer
 * token. Nothing is granted by applying. An admin reads it and decides whether
 * to extend an offer, which is what mints the token and sends the email.
 */
class VolunteerApplicationController extends Controller
{
    public function create(Request $request): View
    {
        return view('volunteer.apply', [
            'roles'    => VolunteerRole::where('is_open', true)->orderBy('title')->get(),
            // Deep link from a role listing preselects that role.
            'selected' => $request->query('role'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot. Real users never see this field, so anything in it is a bot.
        // Answer as though it succeeded rather than showing an error, which
        // tells a scripted submitter nothing.
        //
        // Logged because this branch is indistinguishable from success to
        // whoever submitted: they are shown the thanks page and their
        // application is discarded. The field was named company_website, which
        // Chrome autofill populates, so genuine applicants could be silently
        // dropped while being told it had worked.
        if ($request->filled('vl_reference')) {
            Log::info('Volunteer application honeypot triggered', [
                'ip'         => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 120),
            ]);

            return redirect()->route('volunteer.apply.thanks');
        }

        $validated = $request->validate([
            'volunteer_role_id' => ['required', 'exists:volunteer_roles,id'],
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:40'],
            'about'             => ['required', 'string', 'max:2000'],
            'availability'      => ['required', 'string', 'max:255'],
            'experience'        => ['nullable', 'string', 'max:2000'],
            'consent'           => ['accepted'],
        ], [
            'about.required'        => 'Please tell us a little about why you are interested.',
            'availability.required' => 'Please tell us roughly how much time you can give.',
            'consent.accepted'      => 'Please confirm we can hold your details to consider your application.',
        ]);

        $role = VolunteerRole::where('is_open', true)->find($validated['volunteer_role_id']);

        // The role could have been closed between the form loading and being
        // submitted. Fail here rather than accepting an application against a
        // position that is no longer recruiting.
        if (! $role) {
            return back()
                ->withInput()
                ->with('error', 'That role is no longer open. Please pick another.');
        }

        $alreadyInFlight = VolunteerEngagement::where('offer_email', $validated['email'])
            ->where('volunteer_role_id', $role->id)
            ->whereIn('status', ['applied', 'offer_extended', 'offer_accepted'])
            ->exists();

        if ($alreadyInFlight) {
            // Same answer as success. Someone who applied twice does not need
            // to be told off, and it does not reveal who is already on file.
            return redirect()->route('volunteer.apply.thanks');
        }

        $engagement = VolunteerEngagement::create([
            'volunteer_role_id' => $role->id,
            'user_id'           => User::where('email', $validated['email'])->value('id'),
            'offer_name'        => $validated['name'],
            'offer_email'       => $validated['email'],
            'phone'             => $validated['phone'] ?? null,
            'about'             => $validated['about'],
            'availability'      => $validated['availability'],
            'experience'        => $validated['experience'] ?? null,
            'status'            => 'applied',
            'applied_at'        => now(),
        ]);

        Mail::to(config('mail.volunteer_inbox', 'hello@skillscoop.org'))
            ->send(new VolunteerApplicationReceived($engagement));

        return redirect()->route('volunteer.apply.thanks');
    }

    public function thanks(): View
    {
        return view('volunteer.apply-thanks');
    }
}
