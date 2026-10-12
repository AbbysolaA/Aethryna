<?php

namespace App\Mail;

use App\Models\SessionRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification: someone registered for a panel.
 *
 * Every other inbound pipeline told the team when something arrived;
 * registrations only appeared if somebody went looking at the admin.
 * Reply-to is the registrant, so answering this email writes to them.
 */
class SessionRegistrationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected SessionRegistration $registration)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->replyTo($this->registration->email, $this->registration->name)
            ->view('emails.session-registration-received', $data)
            ->text('emails.session-registration-received-text', $data);
    }

    protected function messagePayload(): array
    {
        $r = $this->registration;
        $panel = $r->panelSession;

        return [
            'subject'      => 'New registration: '.$r->name.' for '.($panel?->tagline ?: 'the next panel'),
            'preheader'    => $r->interestLabel().($r->wants_to_speak ? ', and they offered to speak' : ''),
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are listed as the sessions contact for Skills Co-op.',
            'year'         => date('Y'),

            'name'          => $r->name,
            'email'         => $r->email,
            'interestLabel' => $r->interestLabel(),
            'heardAbout'    => $r->referral_source ? ucfirst(str_replace('_', ' ', $r->referral_source)) : null,
            'campaign'      => $r->attributionLabel() ?: null,
            'wantsToSpeak'  => (bool) $r->wants_to_speak,
            'speakerTopic'  => $r->speaker_topic,
            'panelTagline'  => $panel?->tagline,
            'panelDate'     => $panel?->event_date?->format('l j F Y'),
            // Their registration takes the panel to this count.
            'panelCount'    => $panel
                ? SessionRegistration::where('panel_session_id', $panel->id)->count()
                : null,
            'adminUrl'      => route('admin.registrations.index', $panel ? ['panel' => $panel->id] : []),
        ];
    }
}
