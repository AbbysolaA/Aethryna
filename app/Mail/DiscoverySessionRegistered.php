<?php

namespace App\Mail;

use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The confirmation somebody reads on their phone on the way to the venue.
 *
 * Everything needed to physically get there and through the door: the address,
 * the day, the time, whether it costs anything, whether they can get in, and
 * what will happen once they do. A confirmation that says only "thanks for
 * registering" makes the recipient go and find the page again.
 */
class DiscoverySessionRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SessionRegistration $registration,
        public PanelSession $session,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.discovery-session-registered', $data)
            ->text('emails.discovery-session-registered-text', $data);
    }

    protected function messagePayload(): array
    {
        $s = $this->session;

        // Formatted straight, not converted. event_date holds UK wall-clock
        // time by convention across this table, so reading it as UTC and
        // converting would tell people to arrive an hour after the doors open.
        $date = $s->event_date;

        $waitlisted = $this->registration->waitlisted;

        return [
            'subject' => $waitlisted
                ? 'You are on the waiting list: Community Discovery Session'
                : 'Your place is booked: Community Discovery Session',

            'preheader' => $waitlisted
                ? 'We will email you the moment a place comes up.'
                : ($date?->format('l j F') ?? '').', '.($date?->format('g.ia') ?? '').' · '.$s->venue_name,

            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => config('organisation.email'),
            'footerNote'   => 'You are receiving this because you registered for the Skills Co-op Community Discovery Session.',
            'year'         => date('Y'),

            'firstName'    => $this->registration->firstName(),
            'waitlisted'   => $waitlisted,
            'eventTitle'   => $s->title,
            'tagline'      => $s->tagline,
            'description'  => $s->description,
            'dayAndDate'   => $date?->format('l j F Y'),
            'startTime'    => $date?->format('g.ia'),
            'endTime'      => $date?->copy()->addMinutes(180)->format('g.ia'),
            'venueName'    => $s->venue_name,
            'venueAddress' => $s->venue_address,
            'accessibility' => $s->accessibility_note,
            'itinerary'    => $s->itinerary ?? [],
            'eventUrl'     => $s->url(),
            'mapUrl'       => 'https://www.google.com/maps/search/?api=1&query='.urlencode($s->venue_name.', '.$s->venue_address),
        ];
    }
}
