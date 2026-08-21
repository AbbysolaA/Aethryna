<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\VolunteerRole;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification: someone applied for a paid role.
 *
 * Reply-to is the applicant, so answering this email starts the conversation
 * with them rather than with ourselves.
 */
class JobApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected JobApplication $application,
        protected VolunteerRole $role,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->replyTo($this->application->email, $this->application->name)
            ->view('emails.job-application', $data)
            ->text('emails.job-application-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'Job application, '.$this->role->title,
            'preheader'    => $a->name.' applied for '.$this->role->title,
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are listed as the hiring contact for Skills Co-op.',
            'year'         => date('Y'),

            'applicantName'  => $a->name,
            'applicantEmail' => $a->email,
            'phone'          => $a->phone,
            'roleTitle'      => $this->role->title,
            'coverNote'      => $a->cover_note,
            'portfolioUrl'   => $a->portfolio_url,

            // Named rather than attached, same rule as the volunteer emails:
            // a copy in an inbox is a copy the delete-with-the-row rule
            // cannot reach.
            'cvName'         => $a->hasCv() ? $a->cv_original_name : null,
            'appliedAt'      => $a->created_at?->timezone('Europe/London')->format('j F Y, H:i').' UK time',
            'adminUrl'       => route('admin.job-applications.index'),
        ];
    }
}
