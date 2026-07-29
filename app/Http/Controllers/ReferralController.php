<?php

namespace App\Http\Controllers;

use App\Mail\ReferralReceived;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReferralController extends Controller
{
    public function create()
    {
        return view('referrals.create');
    }

    public function store(Request $request)
    {
        // Honeypot: hidden field real users never fill. Silent redirect rather
        // than a 4xx so a bot learns nothing from the response.
        //
        // Logged because when this misfires it is invisible: the person is sent
        // back to an empty form with no error and no explanation, and there is
        // no way to tell that from the page simply not working. The old field
        // was named company_website, which Chrome autofill populates from a
        // saved profile, so real submissions were being dropped.
        if ($request->filled('rf_reference')) {
            Log::info('Referral honeypot triggered', [
                'ip'         => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 120),
            ]);

            return redirect()->route('referral.create');
        }

        // Someone putting themselves forward rather than referring another
        // person. Decided server side, never inferred from which fields the
        // browser happened to submit, so the form still behaves with no JS.
        $isSelf = $request->boolean('self_referral');

        $validated = $request->validate([
            'referrer_name'         => ['required', 'string', 'max:120'],
            'referrer_email'        => ['required', 'email', 'max:160'],
            'referrer_organisation' => ['nullable', 'string', 'max:160'],
            'referrer_role'         => ['nullable', 'string', 'max:120'],
            // Built as a plain array rather than Rule::requiredIf, which
            // stringifies to an empty rule when the condition is false and can
            // fail to parse. Not asked for when someone is referring
            // themselves; their own name already answers it.
            'referred_first_name'   => $isSelf
                ? ['nullable', 'string', 'max:80']
                : ['required', 'string', 'max:80'],
            'referred_contact'      => ['nullable', 'string', 'max:160'],
            'cohort'                => ['nullable', 'in:neet,justice,returner,unsure'],
            'context'               => ['nullable', 'string', 'max:1000'],
            'consent_confirmed'     => ['nullable', 'boolean'],
        ], [
            'referred_first_name.required' => 'Please give us their first name, or tick the box above if this is about you.',
        ]);

        $validated['is_self_referral'] = $isSelf;

        if ($isSelf) {
            // The two people are the same person, so the referred fields are
            // filled from what they already told us rather than asked twice.
            $validated['referred_first_name'] = $validated['referrer_name'];
            $validated['referred_contact']    = $validated['referrer_email'];

            // Consent means something different here. The third-party box
            // asserts that somebody else agreed to be contacted; this one is
            // the person's own consent, given about their own details, which
            // they have just typed in and asked us to use.
            $consentGiven = true;
        } else {
            // GDPR position: the referred person's contact details are stored
            // only when the referrer explicitly ticks the consent box. If the
            // box is unticked we discard any contact value they typed, so we
            // never hold personal data about a third party without consent.
            $consentGiven = (bool) ($validated['consent_confirmed'] ?? false);

            if (! $consentGiven) {
                $validated['referred_contact'] = null;
            }
        }

        $validated['consent_confirmed'] = $consentGiven;

        $referral = Referral::create($validated);

        // Fail-soft: never lose a referral because email delivery hiccups.
        try {
            Mail::to(config('mail.referral_inbox', 'hello@skillscoop.org'))
                ->send(new ReferralReceived($referral));
        } catch (\Throwable $e) {
            Log::warning('Referral saved but mail delivery failed', [
                'referral_id' => $referral->id,
                'error'       => $e->getMessage(),
            ]);
        }

        // Post/Redirect/Get: redirect rather than rendering the view straight
        // from the POST, so the URL updates and a refresh cannot resubmit.
        return redirect()->route('referral.thanks');
    }

    public function thanks()
    {
        return view('referrals.thanks');
    }
}
