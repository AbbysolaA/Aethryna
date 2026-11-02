<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * To someone who ticked "I'd be open to speaking" on the registration form.
 *
 * The tick captures a maybe and nothing a programme decision can be made
 * from; the pitch form captures a talk. Without this email the two never
 * meet: the person has offered, nobody has answered, and the path from the
 * tick to the form does not exist anywhere they can see.
 */
class SpeakerPitchInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $name,
        protected ?string $topic = null,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->replyTo(config('organisation.email'))
            ->view('emails.speaker-pitch-invite', $data)
            ->text('emails.speaker-pitch-invite-text', $data);
    }

    protected function messagePayload(): array
    {
        return [
            'subject'      => 'You offered to speak. Here is the next step',
            'preheader'    => 'Tell us what you would talk about and we will find it a panel.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you offered to speak when registering for a Skills Co-op panel session.',
            'year'         => date('Y'),

            'firstName' => str($this->name)->before(' ')->toString() ?: $this->name,
            'topic'     => $this->topic,
            // Pinned, never url(): this email also goes out from the command
            // line, where APP_URL is whatever the server thinks it is.
            'pitchUrl'  => 'https://skillscoop.org/apply-to-speak',
        ];
    }
}
