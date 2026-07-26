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
        // Honeypot: hidden field real users never fill.
        // Silent fail (redirect back) rather than a 4xx to avoid tipping bots off.
        if ($request->filled('company_website')) {
            return redirect()->route('referral.create');
        }

        $validated = $request->validate([
            'referrer_name'         => ['required', 'string', 'max:120'],
            'referrer_email'        => ['required', 'email', 'max:160'],
            'referrer_organisation' => ['nullable', 'string', 'max:160'],
            'referrer_role'         => ['nullable', 'string', 'max:120'],
            'referred_first_name'   => ['required', 'string', 'max:80'],
            'referred_contact'      => ['nullable', 'string', 'max:160'],
            'cohort'                => ['nullable', 'in:neet,justice,returner,unsure'],
            'context'               => ['nullable', 'string', 'max:1000'],
            'consent_confirmed'     => ['nullable', 'boolean'],
        ]);

        // GDPR position: the referred person's contact details are stored only
        // when the referrer explicitly ticks the consent box. If the box is
        // unticked we discard any contact value they typed, so we never hold
        // personal data about a third party without their consent.
        $consentGiven = (bool) ($validated['consent_confirmed'] ?? false);
        if (! $consentGiven) {
            $validated['referred_contact'] = null;
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

        return view('referrals.thanks');
    }
}
