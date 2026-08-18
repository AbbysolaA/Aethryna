<?php

namespace App\Mail;

use App\Models\PanelSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SessionRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PanelSession $session,
        public string $name,
        public string $interestType = 'curious',
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.session-registered', $data)
            ->text('emails.session-registered-text', $data);
    }

    /**
     * Shared payload for the HTML and text views. Same pattern as
     * ReferralReceived so both renderings stay in lockstep.
     */
    protected function messagePayload(): array
    {
        $s = $this->session;

        $firstName = trim(explode(' ', trim($this->name))[0]) ?: $this->name;

        $eventDate = $s->event_date?->timezone('Europe/London');

        return [
            // Layout
            'subject'      => 'You are registered — ' . $s->tagline,
            'preheader'    => $s->tagline . ' · ' . ($eventDate?->format('j F, g:ia') ?? 'date TBC') . ' UK',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you registered for a Skills Co-op Sessions panel.',
            'year'         => date('Y'),

            // Content
            'firstName'        => $firstName,
            'interestType'     => $this->interestType,
            'panelTitle'       => $s->tagline,
            'panelDescription' => $s->description,
            'panelDate'        => $eventDate?->format('l, j F Y'),
            'panelTime'        => $eventDate ? $eventDate->format('g:ia') . ' UK time' : 'To be confirmed',
            'shortDate'        => $eventDate?->format('j F'),
            'panelDuration'    => $s->duration ?: '60 minutes',
            'panelFormat'      => $s->format ?: 'Online',
            'eventbriteUrl'    => $s->eventbrite_url,
            'sessionsUrl'      => 'https://skillscoop.org/sessions',
            'speakers'         => $s->relationLoaded('speakers') ? $s->speakers : $s->speakers()->get(),
        ];
    }
}
