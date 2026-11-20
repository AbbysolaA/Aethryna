<?php

namespace App\Mail;

use App\Models\PanelSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * "The prompts are up": one email covering every session named, each with
 * a link to its prompts and to its page, where the recording plays.
 *
 * Links go to the session page rather than the raw recording, because a
 * StreamYard recording's address is a player, not a page anyone should
 * land on from an inbox.
 */
class PromptsAvailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Protected, not public: a Mailable hands its public properties to the
     * template, where these would overwrite the prepared session data of
     * the same name.
     *
     * @param Collection<int, PanelSession> $sessions
     */
    public function __construct(
        protected Collection $sessions,
        protected string $name,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->replyTo(config('organisation.email'))
            ->view('emails.prompts-available', $data)
            ->text('emails.prompts-available-text', $data);
    }

    protected function messagePayload(): array
    {
        // Pinned to the public domain: this goes out from the command line,
        // where APP_URL is whatever the server thinks it is.
        $pageUrl = fn (PanelSession $p) => 'https://skillscoop.org/sessions/'.$p->slug;

        $sessions = $this->sessions->map(function (PanelSession $p) use ($pageUrl) {
            $prompts = $p->prompts()->with('speaker')->get();
            $builders = $prompts->pluck('speaker.name')->filter()->unique()->values();
            $speakers = $p->relationLoaded('speakers') ? $p->speakers : $p->speakers()->get();

            return [
                'tagline'     => $p->tagline,
                'date'        => $p->event_date?->format('l j F Y'),
                'builders'    => $this->sentenceList($builders->all()),
                'promptsUrl'  => $pageUrl($p).'#prompts',
                'pageUrl'     => $pageUrl($p),
                'hasRecording'=> (bool) $p->recording_url,
                'moreToCome'  => $speakers->pluck('name')->diff($builders)->isNotEmpty(),
            ];
        });

        return [
            'subject'      => 'Build it yourself: the prompts from our live builds',
            'preheader'    => 'The exact prompts our speakers used on screen are now on our website, free.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you registered for a Skills Co-op session. If you would rather not hear about future sessions, reply and say so and we will take you off the list.',
            'year'         => date('Y'),

            'firstName'  => trim(explode(' ', trim($this->name))[0]) ?: 'there',
            'sessions'   => $sessions,
            'moreToCome' => $sessions->contains('moreToCome', true),
        ];
    }

    /** "A", "A and B", "A, B and C": no Oxford comma, per house style. */
    private function sentenceList(array $items): string
    {
        if (count($items) <= 1) {
            return (string) ($items[0] ?? '');
        }

        return implode(', ', array_slice($items, 0, -1)).' and '.end($items);
    }
}
