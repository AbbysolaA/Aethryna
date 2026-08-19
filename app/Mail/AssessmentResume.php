<?php

namespace App\Mail;

use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

/**
 * The link back into an unfinished assessment.
 *
 * Two reasons to send it, and the difference matters enough to change the
 * wording rather than send one vague message for both:
 *
 *   saved    — they asked for it. Confirmatory, no persuasion needed.
 *   reminder — we noticed they stopped. Sent once, and it has to be easy to
 *              ignore, because they did not ask for it.
 */
class AssessmentResume extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Assessment $assessment,
        public string $reason = 'saved',
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.assessment-resume', $data)
            ->text('emails.assessment-resume-text', $data);
    }

    /**
     * One-click unsubscribe, per RFC 8058.
     *
     * List-Unsubscribe on its own is old and widely ignored by senders; the
     * pairing with List-Unsubscribe-Post is what makes Gmail and Apple Mail
     * render a real unsubscribe control at the top of the message, so somebody
     * who does not want this can stop it in one tap without reading the footer
     * or composing a reply.
     *
     * The mailto: fallback is second on purpose — clients prefer the first
     * usable option, and the URL resolves without a human reading an inbox.
     *
     * Laravel hydrates this after build(), so the two coexist
     * (Mailable::prepareMailableForDelivery).
     */
    public function headers(): Headers
    {
        $url = $this->assessment->unsubscribeUrl();

        return new Headers(text: [
            'List-Unsubscribe' => '<' . $url . '>, <mailto:hello@skillscoop.org?subject=unsubscribe>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    protected function messagePayload(): array
    {
        $assessment = $this->assessment->loadMissing('user');

        $answered = $assessment->answeredCount();
        $total    = Question::active()->count();
        $left     = max($total - $answered, 0);
        $isNudge  = $this->reason === 'reminder';

        return [
            // Layout
            'subject' => $isNudge
                ? 'You were ' . $answered . ' questions in — pick it back up?'
                : 'Your link back to the pathway assessment',
            'preheader' => $isNudge
                ? 'Your answers are saved. ' . ($left > 0 ? $left . ' questions left.' : 'Nearly there.')
                : 'Open this whenever you are ready. Your answers are waiting.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => $isNudge
                ? 'You are getting this once, because you asked us to save your place on the skillscoop.org pathway assessment. We will not email you about it again.'
                : 'You are receiving this because you asked us to save your place on the skillscoop.org pathway assessment.',
            'year' => date('Y'),

            // Content
            'firstName' => $assessment->recipientFirstName(),
            'isNudge'   => $isNudge,
            'answered'  => $answered,
            'total'     => $total,
            'left'      => $left,
            'resumeUrl' => $assessment->resumeUrl(),

            // Also printed in the body. The header is invisible in plenty of
            // clients, and "unsubscribe" is not a thing people should have to
            // know to look for in their mail client's chrome.
            'unsubscribeUrl' => $assessment->unsubscribeUrl(),
        ];
    }
}
