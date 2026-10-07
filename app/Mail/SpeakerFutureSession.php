<?php

namespace App\Mail;

use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Told honestly: not this session, and genuinely wanted for a later one.
 *
 * Without this email, "keep for a future session" is a note in our admin
 * that the speaker experiences as silence, which reads as a decline. The
 * whole point of the status is that the person hears the difference.
 */
class SpeakerFutureSession extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected SpeakerApplication $application)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            // Replies must land somewhere a person reads, whatever the From
            // address is.
            ->replyTo(config('organisation.email'))
            ->view('emails.speaker-future-session', $data)
            ->text('emails.speaker-future-session-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'Your pitch: we want it for a future session',
            'preheader'    => 'Not the session we are casting now, and genuinely one we want to run.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you pitched a talk to Skills Co-op.',
            'year'         => date('Y'),

            'firstName' => str($a->name)->before(' ')->toString() ?: $a->name,
            'talkTitle' => $a->talk_title,
        ];
    }
}
