<?php

namespace App\Mail;

use App\Models\VolunteerEngagement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification that someone has applied to volunteer.
 *
 * Without this an application sits in the roster unseen until someone thinks
 * to look, which for a volunteer who has just put themselves forward is a poor
 * first impression.
 */
class VolunteerApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    // Protected so it cannot leak into the view data and shadow a payload key,
    // the way a public $documents did on VolunteerWelcome.
    public function __construct(protected VolunteerEngagement $engagement)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->replyTo($this->engagement->offer_email, $this->engagement->offer_name)
            ->view('emails.volunteer-application', $data)
            ->text('emails.volunteer-application-text', $data);
    }

    protected function messagePayload(): array
    {
        $e    = $this->engagement;
        $role = $e->role;

        return [
            // Layout variables
            'subject'      => 'Volunteer application, ' . $role->title,
            'preheader'    => $e->offer_name . ' applied for ' . $role->title,
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you are listed as a volunteering contact for Skills Co-op.',
            'year'         => date('Y'),

            // Content variables
            'applicantName'  => $e->offer_name,
            'applicantEmail' => $e->offer_email,
            'phone'          => $e->phone,
            'roleTitle'      => $role->title,
            'about'          => $e->about,
            'availability'   => $e->availability,
            'experience'     => $e->experience,
            'appliedAt'      => $e->applied_at?->timezone('Europe/London')->format('j F Y, H:i') . ' UK time',
            'rosterUrl'      => route('admin.volunteers.index'),
        ];
    }
}
