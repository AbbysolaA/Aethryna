<?php

namespace App\Mail;

use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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
        ];
    }
}
