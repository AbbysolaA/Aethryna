<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\VolunteerRole;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The receipt an applicant keeps.
 *
 * Applying for a job and hearing nothing is the default experience of
 * jobseeking, and it is not one this organisation should hand out. This
 * confirms what they applied for and what happens next, and promises an
 * answer either way, which is a promise the admin screen makes keepable.
 */
class JobApplicationConfirmation extends Mailable
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
            ->view('emails.job-application-confirmation', $data)
            ->text('emails.job-application-confirmation-text', $data);
    }

    protected function messagePayload(): array
    {
        $a = $this->application;

        return [
            'subject'      => 'We have your application: '.$this->role->title,
            'preheader'    => 'It is in front of a person, not a filter. You will hear back either way.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you applied for a role at Skills Co-op.',
            'year'         => date('Y'),

            // First name for the greeting, the way the site's other
            // confirmations address people.
            'firstName' => str($a->name)->before(' ')->toString() ?: $a->name,
            'roleTitle' => $this->role->title,
            'cvName'    => $a->cv_original_name,
            'roleUrl'   => $this->role->url(),
        ];
    }
}
