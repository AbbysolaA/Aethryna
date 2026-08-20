<?php

namespace App\Mail;

use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The internal heads-up when somebody registers.
 *
 * Deliberately plain and deliberately complete. Its job is to put an access
 * requirement in front of a human days before the event rather than leaving it
 * in a table nobody opens until the morning — so anything written in the notes
 * field leads, and the running count comes with it so the room's limit is
 * visible without logging in.
 *
 * Reply-to is the registrant, so answering a question about access is a reply
 * rather than a copy and paste.
 */
class DiscoverySessionStaffNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SessionRegistration $registration,
        public PanelSession $session,
    ) {
    }

    public function build()
    {
        $r = $this->registration;

        $confirmed = $this->session->confirmedCount();
        $capacity  = $this->session->capacity;

        $flag = $r->waitlisted ? '[WAITLIST] ' : '';
        $note = filled($r->notes) ? ' (has a note)' : '';

        return $this
            ->subject($flag.'Discovery Session registration: '.$r->name.$note)
            ->replyTo($r->email, $r->name)
            ->view('emails.discovery-session-staff', [
                'registration' => $r,
                'session'      => $this->session,
                'confirmed'    => $confirmed,
                'capacity'     => $capacity,
                'spacesLeft'   => $this->session->spacesLeft(),
                'waitlistCount' => $this->session->registrations()->waitlisted()->count(),
            ])
            ->text('emails.discovery-session-staff-text', [
                'registration' => $r,
                'session'      => $this->session,
                'confirmed'    => $confirmed,
                'capacity'     => $capacity,
                'spacesLeft'   => $this->session->spacesLeft(),
                'waitlistCount' => $this->session->registrations()->waitlisted()->count(),
            ]);
    }
}
