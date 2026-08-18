<?php

namespace App\Mail;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Assessment $assessment)
    {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.assessment-results', $data)
            ->text('emails.assessment-results-text', $data);
    }

    protected function messagePayload(): array
    {
        $assessment = $this->assessment->loadMissing('results.pathway', 'user');

        // recipientFirstName() falls back to the name given on the assessment
        // itself, so anonymous completers are greeted by name rather than as
        // "there" — most people who take this have no account.
        $firstName = $assessment->recipientFirstName();

        $primary   = $assessment->results->firstWhere('result_type', 'primary');
        $secondary = $assessment->results->firstWhere('result_type', 'secondary');

        $primaryName = $primary?->pathway?->name;

        return [
            // Layout
            'subject'      => $primaryName
                ? 'Your pathway match: ' . $primaryName
                : 'Your Skills Co-op assessment results',
            'preheader'    => $primaryName
                ? $primaryName . ' came out as your closest match. Here is why.'
                : 'Your assessment results are ready.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because you completed the pathway assessment on skillscoop.org.',
            'year'         => date('Y'),

            // Content
            'firstName'    => $firstName,
            'primary'      => $primary,
            'secondary'    => $secondary,
            'resultsUrl'   => 'https://skillscoop.org/assessment/results',
            'pathwayUrl'   => 'https://skillscoop.org/pathway',
            'programsUrl'  => 'https://skillscoop.org/programs',
            'sessionsUrl'  => 'https://skillscoop.org/sessions',
        ];
    }
}
