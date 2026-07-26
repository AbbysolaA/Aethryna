<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Referral $referral)
    {
    }

    public function build()
    {
        $data = $this->buildViewData();

        return $this
            ->subject($data['subject'])
            ->replyTo($this->referral->referrer_email, $this->referral->referrer_name)
            ->view('emails.referral-received', $data)
            ->text('emails.referral-received-text', $data);
    }

    /**
     * Prepare the payload for both the HTML and text views. Keeping this in
     * one place means the two renderings can never disagree — and puts the
     * consent gating in code, not in template logic.
     */
    protected function buildViewData(): array
    {
        $r = $this->referral;

        $cohortTitle = $r->cohort ? ucfirst($r->cohort) : null;
        $consented   = (bool) $r->consent_confirmed;

        // Suppress the referred person's contact entirely unless consent is
        // recorded, so a leak here cannot happen even if the DB was populated
        // out-of-band. This mirrors HANDOVER §6.
        $rawContact = ($consented && ! empty($r->referred_contact)) ? $r->referred_contact : null;
        [$contact, $contactHref, $contactIsEmail] = $this->resolveContact($rawContact);

        $submittedAt = $r->created_at
            ?->timezone('Europe/London')
            ->format('j F Y, H:i') . ' UK time';

        return [
            // Layout variables
            'subject'      => 'New referral received — ' . $r->referred_first_name,
            'preheader'    => trim($r->referred_first_name
                . ' · ' . ($cohortTitle ?? 'cohort unsure')
                . ' · referred by ' . $r->referrer_name),
            'logoUrl'      => 'https://skillscoop.org/images/logo_white.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are listed as a referral contact for Skills Co-op. Contact details are only shared with the referred person\'s consent.',
            'year'         => date('Y'),

            // Content variables
            'referredName'     => $r->referred_first_name,
            'cohort'           => $cohortTitle,
            'contact'          => $contact,
            'contactHref'      => $contactHref,
            'contactIsEmail'   => $contactIsEmail,
            'contactConsented' => $consented,
            'referrerName'     => $r->referrer_name,
            'referrerEmail'    => $r->referrer_email,
            'organisation'     => $r->referrer_organisation,
            'role'             => $r->referrer_role,
            'context'          => $r->context,
            'submittedAt'      => $submittedAt,
            'dashboardUrl'     => null,
        ];
    }

    /**
     * Detect whether the contact string is an email or a phone number, and
     * produce the appropriate href. UK numbers starting with 0 are normalised
     * to +44 for the tel: link; the display form is left as the referrer
     * typed it.
     *
     * @return array{0: ?string, 1: ?string, 2: bool} [display, href, isEmail]
     */
    protected function resolveContact(?string $raw): array
    {
        if ($raw === null || $raw === '') {
            return [null, null, false];
        }

        if (str_contains($raw, '@')) {
            return [$raw, 'mailto:' . $raw, true];
        }

        $digits = preg_replace('/[^\d+]/', '', $raw);
        $tel    = str_starts_with($digits, '0')
            ? '+44' . substr($digits, 1)
            : $digits;

        return [$raw, 'tel:' . $tel, false];
    }
}
