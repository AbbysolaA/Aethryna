<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.welcome', $data)
            ->text('emails.welcome-text', $data);
    }

    protected function messagePayload(): array
    {
        $firstName = trim(explode(' ', trim($this->user->name))[0]) ?: $this->user->name;

        $nextPanel = \App\Models\PanelSession::upcoming()->first();

        return [
            // Layout
            'subject'      => 'Welcome to Skills Co-op, ' . $firstName,
            'preheader'    => 'Your account is live. Here is what to do first.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you created a Skills Co-op account.',
            'year'         => date('Y'),

            // Content
            'firstName'     => $firstName,
            'assessmentUrl' => 'https://skillscoop.org/assessment',
            'pathwayUrl'    => 'https://skillscoop.org/pathway',
            'sessionsUrl'   => 'https://skillscoop.org/sessions',
            'nextPanel'     => $nextPanel,
            // Not converted: event_date is UK wall-clock, not a UTC instant.
            // Only the date is shown here so nothing visible was wrong, but the
            // same conversion an hour before midnight would print the wrong day.
            'panelDate'     => $nextPanel?->event_date?->format('j F Y'),
            'panelTitle'    => $nextPanel?->tagline,
        ];
    }
}
