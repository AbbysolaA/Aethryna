<?php

namespace App\Mail;

use App\Models\PanelSession;
use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The yes, in one of two shapes.
 *
 * With a panel chosen it carries the session, the date and the format, so
 * the speaker leaves the email knowing where they stand. Without one it
 * says plainly that the talk is wanted and the session details follow, so
 * an acceptance never reads as silence while the programming is decided.
 */
class SpeakerAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected SpeakerApplication $application,
        protected ?PanelSession $panel = null,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            // Replies must land somewhere a person reads, whatever the From
            // address is.
            ->replyTo(config('organisation.email'))
            ->view('emails.speaker-accepted', $data)
            ->text('emails.speaker-accepted-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;
        $p = $this->panel;

        return [
            'subject'      => $p ? 'Your talk is booked: '.$p->tagline : 'Your pitch is accepted',
            'preheader'    => $p
                ? 'We want this talk, and it has a home: '.$p->tagline
                : 'We want this talk. The session details follow once it is matched to the right one.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you pitched a talk to Skills Co-op.',
            'year'         => date('Y'),

            'firstName'   => str($a->name)->before(' ')->toString() ?: $a->name,
            'talkTitle'   => $a->talk_title,
            'panelTagline' => $p?->tagline,
            'panelDate'   => $p?->event_date?->format('l j F Y, g.ia'),
            'panelFormat' => $p?->format,
        ];
    }
}
