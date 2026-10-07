<?php

namespace App\Mail;

use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The no, said kindly and said at all.
 *
 * A decline the speaker never hears is experienced as weeks of waiting
 * followed by nothing, which costs us exactly the people confident enough
 * to pitch. This one is short, honest about fit and genuinely open about
 * pitching again.
 */
class SpeakerDeclined extends Mailable
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
            ->view('emails.speaker-declined', $data)
            ->text('emails.speaker-declined-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'Your pitch: not this time, and please pitch again',
            'preheader'    => 'We read it properly. This one is not the right fit for what we are casting.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you pitched a talk to Skills Co-op.',
            'year'         => date('Y'),

            'firstName' => str($a->name)->before(' ')->toString() ?: $a->name,
            'talkTitle' => $a->talk_title,
        ];
    }
}
