<?php

namespace App\Mail;

use App\Models\SafeguardingConcern;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SafeguardingConcernRaised extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SafeguardingConcern $concern)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        $mail = $this
            ->subject($data['subject'])
            ->view('emails.safeguarding-concern', $data)
            ->text('emails.safeguarding-concern-text', $data);

        // Let the lead reply straight to the person who raised it.
        if ($this->concern->raisedBy?->email) {
            $mail->replyTo($this->concern->raisedBy->email, $this->concern->raisedBy->name);
        }

        if ($this->concern->urgency === 'urgent') {
            $mail->priority(1);
        }

        return $mail;
    }

    protected function messagePayload(): array
    {
        $c = $this->concern->loadMissing('raisedBy', 'learner');

        $isUrgent = $c->urgency === 'urgent';
        $ref      = 'SC-' . $c->id;

        return [
            // Layout
            'subject'      => ($isUrgent ? 'URGENT safeguarding concern' : 'Safeguarding concern')
                . ' (' . $ref . '): ' . ($c->learner?->name ?? 'learner'),
            'preheader'    => $ref . ' raised by ' . ($c->raisedBy?->name ?? 'a team member')
                . ' and awaiting your review.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are the named safeguarding contact for Skills Co-op. This message contains personal data. Do not forward it outside the safeguarding process.',
            'year'         => date('Y'),

            // Content
            'reference'    => $ref,
            'isUrgent'     => $isUrgent,
            'urgencyLabel' => $isUrgent ? 'Urgent' : 'Routine',
            'learnerName'  => $c->learner?->name ?? 'Unknown learner',
            'learnerEmail' => $c->learner?->email,
            'raiserName'   => $c->raisedBy?->name ?? 'Unknown',
            'raiserEmail'  => $c->raisedBy?->email,
            'raiserRole'   => $c->raised_by_role ? ucfirst($c->raised_by_role) : 'Not recorded',
            'concernBody'  => $c->concern,
            'raisedAt'     => $c->created_at?->timezone('Europe/London')->format('j F Y, H:i') . ' UK time',
        ];
    }
}
