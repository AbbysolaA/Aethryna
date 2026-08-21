<?php

namespace App\Mail;

use App\Models\VolunteerEngagement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The receipt a volunteer applicant keeps.
 *
 * Every other application on the site answers with an email: jobs, speaker
 * pitches, event registrations. Volunteers and mentors only saw a thanks page,
 * which is gone the moment the tab closes, and mentors are people we are
 * asking to give time for nothing. They should not also be the only ones we
 * do not write back to.
 */
class VolunteerApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected VolunteerEngagement $engagement)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.volunteer-application-confirmation', $data)
            ->text('emails.volunteer-application-confirmation-text', $data);
    }

    protected function messagePayload(): array
    {
        $e = $this->engagement;

        return [
            'subject'      => 'We have your application: '.$e->role->title,
            'preheader'    => 'A person reads every application. You will hear back either way.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you applied to volunteer with Skills Co-op.',
            'year'         => date('Y'),

            'firstName' => str($e->offer_name)->before(' ')->toString() ?: $e->offer_name,
            'roleTitle' => $e->role->title,
            'cvName'    => $e->hasCv() ? $e->cv_original_name : null,
        ];
    }
}
