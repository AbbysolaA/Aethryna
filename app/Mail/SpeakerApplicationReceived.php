<?php

namespace App\Mail;

use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Internal notification: someone pitched a talk. */
class SpeakerApplicationReceived extends Mailable
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
            ->replyTo($this->application->email, $this->application->name)
            ->view('emails.speaker-application', $data)
            ->text('emails.speaker-application-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'Speaker pitch: '.$a->talk_title,
            'preheader'    => $a->name.' wants to speak at a session',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are listed as the sessions contact for Skills Co-op.',
            'year'         => date('Y'),

            'speakerName'  => $a->name,
            'speakerEmail' => $a->email,
            'affiliation'  => collect([$a->job_title, $a->organisation])->filter()->implode(', '),
            'talkTitle'    => $a->talk_title,
            'talkSummary'  => $a->talk_summary,
            'bio'          => $a->bio,
            'adminUrl'     => route('admin.speaker-applications.index'),
        ];
    }
}
