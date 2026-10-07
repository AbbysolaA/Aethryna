<?php

namespace App\Mail;

use App\Models\SpeakerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * A message written by a person, sent from the pitch page.
 *
 * The body goes out exactly as the admin wrote it, wrapped in the house
 * layout. No generated greeting or sign-off: the form pre-fills a skeleton
 * the sender edits, so the words in the email are always words a person
 * chose to send.
 */
class SpeakerPitchReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected SpeakerApplication $application,
        protected string $subjectLine,
        protected string $messageBody,
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
            // A copy to the shared inbox, so when the speaker replies the
            // thread they are replying to is sitting right there.
            ->bcc(config('organisation.email'))
            ->view('emails.speaker-pitch-reply', $data)
            ->text('emails.speaker-pitch-reply-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => $this->subjectLine,
            'preheader'    => str($this->messageBody)->squish()->limit(120)->toString(),
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you pitched a talk to Skills Co-op.',
            'year'         => date('Y'),

            'talkTitle' => $a->talk_title,
            'body'      => $this->messageBody,
            // Blank lines split paragraphs; single newlines inside one
            // survive as line breaks in the HTML view.
            'paragraphs' => preg_split('/\R{2,}/', trim($this->messageBody)) ?: [],
        ];
    }
}
