<?php

namespace App\Mail;

use App\Models\PanelSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Watch it back: the panel happened, here is the recording.
 *
 * Goes to everyone in the registrations bank, once, via panels:recap.
 * Also carries the YouTube subscribe nudge, because the full demo videos
 * land there, and the next session for anyone ready to book again.
 */
class PanelRecap extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PanelSession $panel,
        public string $name,
        public ?PanelSession $nextPanel = null,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            // Replies must land somewhere a person reads, whatever the From
            // address is.
            ->replyTo(config('organisation.email'))
            ->view('emails.panel-recap', $data)
            ->text('emails.panel-recap-text', $data);
    }

    protected function messagePayload(): array
    {
        $p = $this->panel;

        $youtube = collect(config('organisation.same_as', []))
            ->first(fn ($url) => str_contains($url, 'youtube.com'));

        return [
            'subject'      => 'Watch it back: '.$p->tagline,
            'preheader'    => 'The full recording is up, and the demo videos are coming to YouTube.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you registered for a Skills Co-op session. If you would rather not hear about future sessions, reply and say so and we will take you off the list.',
            'year'         => date('Y'),

            'firstName'    => trim(explode(' ', trim($this->name))[0]) ?: 'there',
            'panelTitle'   => $p->tagline,
            'panelDate'    => $p->event_date?->format('l j F Y'),
            'recordingUrl' => $p->recording_url,
            'panelUrl'     => $p->url(),
            'speakers'     => $p->relationLoaded('speakers') ? $p->speakers : $p->speakers()->get(),
            'youtubeUrl'   => $youtube,
            'nextTagline'  => $this->nextPanel?->tagline,
            'nextDate'     => $this->nextPanel?->event_date?->format('l j F Y, g.ia'),
            'nextUrl'      => $this->nextPanel?->url(),
        ];
    }
}
