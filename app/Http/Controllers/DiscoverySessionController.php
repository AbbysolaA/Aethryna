<?php

namespace App\Http\Controllers;

use App\Mail\DiscoverySessionRegistered;
use App\Mail\DiscoverySessionStaffNotification;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * The Community Discovery Session: its page, and signing up for it.
 *
 * Separate from PageController::registerSession because the two events ask
 * different questions. An online panel needs a name and an email. A room in
 * Birkenhead needs a phone number in case the venue changes on the morning, an
 * access requirement before someone arrives rather than after, and a fire limit
 * that has to be respected without turning anyone away.
 */
class DiscoverySessionController extends Controller
{
    private const SLUG = 'discovery-session';

    public function show()
    {
        return view('events.discovery-session', [
            'session'  => $this->session(),
            'pathways' => config('organisation.pathways', []),
            'groups'   => SessionRegistration::audienceGroups(),
        ]);
    }

    public function register(Request $request)
    {
        $session = $this->session();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email:rfc', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:40'],
            'audience_group' => ['nullable', Rule::in(array_keys(SessionRegistration::audienceGroups()))],
            'notes'      => ['nullable', 'string', 'max:2000'],

            // "accepted" rather than "boolean": an unticked checkbox is absent
            // from the request entirely, and consent that defaults to true is
            // not consent.
            'consent'    => ['accepted'],
        ], [
            'consent.accepted' => 'We need your agreement to contact you about the event before you can register.',
        ]);

        // A bot filling every field it can find. Real people never see this,
        // so anything in it came from something that was not reading the page.
        if (filled($request->input('website'))) {
            return back()->with('success', $this->confirmationMessage(false));
        }

        $existing = SessionRegistration::where('panel_session_id', $session->id)
            ->where('email', $data['email'])
            ->first();

        // Someone already holding a place who registers again keeps their
        // place. Recomputing from capacity would push them onto the waiting
        // list behind people who registered after them.
        $waitlisted = $existing
            ? $existing->waitlisted
            : $session->isFull();

        $registration = SessionRegistration::updateOrCreate(
            [
                'panel_session_id' => $session->id,
                'email'            => $data['email'],
            ],
            [
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'phone'          => $data['phone'] ?? null,
                'audience_group' => $data['audience_group'] ?? null,
                'notes'          => $data['notes'] ?? null,
                'consented_at'   => now(),
                'waitlisted'     => $waitlisted,

                // interest_type is NOT NULL and an enum the online panels
                // depend on, so it is mapped rather than extended: someone who
                // picked one of the three groups is a prospective learner,
                // anyone else is curious until they tell us otherwise.
                'interest_type'  => in_array($data['audience_group'] ?? null, ['neet', 'justice', 'returning'], true)
                    ? 'learner'
                    : 'curious',

                'referral_source' => 'Discovery Session page',
            ]
        );

        $this->sendEmails($registration, $session);

        return back()
            ->with('success', $this->confirmationMessage($waitlisted))
            ->with('waitlisted', $waitlisted);
    }

    /**
     * Neither email is allowed to cost someone their registration.
     *
     * The row is already saved by this point. A mail server having a bad
     * afternoon should show up in the log, not as a 500 on a page somebody
     * reached from a flyer.
     */
    private function sendEmails(SessionRegistration $registration, PanelSession $session): void
    {
        try {
            Mail::to($registration->email)->send(new DiscoverySessionRegistered($registration, $session));
        } catch (\Throwable $e) {
            Log::warning('Discovery Session confirmation email failed', [
                'registration' => $registration->id,
                'error'        => $e->getMessage(),
            ]);
        }

        try {
            Mail::to(config('organisation.email'))->send(new DiscoverySessionStaffNotification($registration, $session));
        } catch (\Throwable $e) {
            Log::warning('Discovery Session staff notification failed', [
                'registration' => $registration->id,
                'error'        => $e->getMessage(),
            ]);
        }
    }

    private function confirmationMessage(bool $waitlisted): string
    {
        return $waitlisted
            ? 'You are on the waiting list. Places come up more often than you would think, and we will email you the moment one does.'
            : 'Your place is booked. Check your email for the details, and come as you are.';
    }

    private function session(): PanelSession
    {
        return PanelSession::where('slug', self::SLUG)->firstOrFail();
    }
}
