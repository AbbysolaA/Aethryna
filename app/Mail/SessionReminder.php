<?php

namespace App\Mail;

use App\Models\PanelSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The nudge that gets registrants into the room.
 *
 * A free online event loses a large share of its registrants to simple
 * forgetting. Two reminders, a week out and the day before, are most of
 * what Eventbrite was earning its keep with. Stage is 'week' or 'day';
 * the day email carries the calendar file again because the day before
 * is when people finally plan.
 */
class SessionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PanelSession $session,
        public string $name,
        public string $stage = 'week',
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        $mail = $this
            ->subject($data['subject'])
            // Replies must land somewhere a person reads, whatever the From
            // address is.
            ->replyTo(config('organisation.email'))
            ->view('emails.session-reminder', $data)
            ->text('emails.session-reminder-text', $data);

        if ($this->session->event_date) {
            $mail->attachData($this->session->toIcs(), $this->session->slug.'.ics', [
                'mime' => 'text/calendar',
            ]);
        }

        return $mail;
    }

    protected function messagePayload(): array
    {
        $s = $this->session;

        // Formatted straight, not converted: event_date holds UK wall-clock
        // time, the reading everyone was shown when they registered.
        $eventDate = $s->event_date;

        return [
            'subject'      => $this->stage === 'day'
                ? 'Tomorrow: '.$s->tagline
                : 'One week to go: '.$s->tagline,
            'preheader'    => ($eventDate?->format('l j F, g.ia') ?? '').' UK. Free and online, your seat is booked.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you registered for a Skills Co-op Sessions panel.',
            'year'         => date('Y'),

            'stage'       => $this->stage,
            'firstName'   => trim(explode(' ', trim($this->name))[0]) ?: $this->name,
            'panelTitle'  => $s->tagline,
            'panelDate'   => $eventDate?->format('l j F Y'),
            'panelTime'   => $eventDate ? $eventDate->format('g.ia').' UK time' : null,
            'panelFormat' => $s->format ?: 'Online',
            'panelUrl'    => $s->url(),
            'calendarUrl' => route('sessions.calendar', $s),
            'speakers'    => $s->relationLoaded('speakers') ? $s->speakers : $s->speakers()->get(),
        ];
    }
}
