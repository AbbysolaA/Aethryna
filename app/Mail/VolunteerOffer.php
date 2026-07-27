<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Sent when a volunteer is selected for a role and needs to accept or decline.
 *
 * The email deliberately carries no accept/decline links of its own. The
 * decision is made on the site behind a login, so an accepted offer is always
 * tied to a real authenticated account rather than to whoever happened to
 * receive a forwarded email.
 */
class VolunteerOffer extends Mailable implements MailableContract
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $role,
        public Carbon $startsOn,
        public Carbon $endsOn,
        public string $respondUrl,
        public ?Carbon $respondBy = null,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.volunteer-offer', $data)
            ->text('emails.volunteer-offer-text', $data);
    }

    /**
     * Named messagePayload() rather than buildViewData() so we do not override
     * the parent Mailable method Laravel calls at render time. Mirrors
     * ReferralReceived.
     */
    protected function messagePayload(): array
    {
        $respondBy = $this->respondBy
            ?? Carbon::now()->addDays((int) config('volunteering.offer_response_days', 14));

        return [
            // Layout variables
            'subject'      => 'Your volunteer offer, ' . $this->role,
            'preheader'    => 'Accept or decline by ' . $respondBy->format('j F'),
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you applied to volunteer with Skills Co-op.',
            'year'         => date('Y'),

            // Content variables
            'firstName'  => $this->firstName,
            'role'       => $this->role,
            'startsOn'   => $this->startsOn->format('j F Y'),
            'endsOn'     => $this->endsOn->format('j F Y'),
            'respondUrl' => $this->respondUrl,
            'respondBy'  => $respondBy->format('j F Y'),
        ];
    }
}
