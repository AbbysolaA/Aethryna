<?php

namespace App\Mail;

use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The receipt a would-be speaker keeps.
 *
 * Pitching a talk takes nerve, first-timers most of all, and the site says
 * first-timers are welcome. Silence after pressing send would say otherwise,
 * so this lands straight away with what happens next.
 */
class SpeakerApplicationConfirmation extends Mailable
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
            ->view('emails.speaker-application-confirmation', $data)
            ->text('emails.speaker-application-confirmation-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'We have your pitch: '.$a->talk_title,
            'preheader'    => 'A person reads every pitch. You will hear back either way.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you applied to speak at a Skills Co-op session.',
            'year'         => date('Y'),

            'firstName' => str($a->name)->before(' ')->toString() ?: $a->name,
            'talkTitle' => $a->talk_title,
        ];
    }
}
