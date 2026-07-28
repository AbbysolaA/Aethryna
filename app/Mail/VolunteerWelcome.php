<?php

namespace App\Mail;

use App\Models\VolunteerDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent once a volunteer has accepted their offer. Carries the onboarding pack.
 *
 * The pack is whatever is uploaded and active under Admin > Onboarding pack,
 * so it changes without a deploy.
 */
class VolunteerWelcome extends Mailable implements MailableContract
{
    use Queueable, SerializesModels;

    /**
     * @param  list<string>|null  $actions  Opening steps. Rendered unescaped so
     *                                      <strong> works, so these must stay
     *                                      author-controlled. Never pass user
     *                                      input here.
     * @param  list<array{label:string,note:string,url:?string}>|null  $documents
     */
    /**
     * These are protected, not public, and that matters.
     *
     * Mailable::buildViewData() reflects over PUBLIC properties and merges them
     * ON TOP of the view data passed to view(). A public $documents left null
     * therefore overwrote the resolved pack with null, and the template died on
     * foreach(null). Protected properties are invisible to that reflection, so
     * messagePayload() stays the single source of what the view receives.
     */
    public function __construct(
        protected string $firstName,
        protected string $role,
        protected ?string $firstCommitments = null,
        protected ?array $actions = null,
        protected ?array $documents = null,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.volunteer-welcome', $data)
            ->text('emails.volunteer-welcome-text', $data);
    }

    protected function messagePayload(): array
    {
        return [
            // Layout variables
            'subject'      => 'Welcome to Skills Co-op',
            'preheader'    => 'Your onboarding pack, and the two things to do first.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'This message and the documents linked from it are confidential and intended for the named recipient only.',
            'year'         => date('Y'),

            // Content variables
            'firstName'        => $this->firstName,
            'role'             => $this->role,
            'firstCommitments' => $this->firstCommitments,
            'actions'          => $this->actions ?? $this->defaultActions(),
            'documents'        => $this->resolveDocuments(),
        ];
    }

    /**
     * @return list<string>
     */
    protected function defaultActions(): array
    {
        return [
            'Read and sign the <strong>Volunteer Agreement</strong> and the <strong>Non-Disclosure Agreement</strong>, and return both to me.',
            'Complete your <strong>Basic DBS check</strong> using the link I will send separately.',
        ];
    }

    /**
     * The pack, read from the documents uploaded in the admin.
     *
     * Links point at the gated download route rather than at the files, which
     * sit on a disk the web server does not serve. The recipient always has an
     * account by this point, because accepting the offer required signing in.
     *
     * @return list<array{label:string,note:string,url:string}>
     */
    protected function resolveDocuments(): array
    {
        if ($this->documents !== null) {
            return $this->documents;
        }

        return VolunteerDocument::active()
            ->inPackOrder()
            ->get()
            ->map(fn (VolunteerDocument $doc): array => [
                'label' => $doc->label,
                'note'  => $doc->note ?? '',
                'url'   => $doc->url(),
            ])
            ->all();
    }
}
