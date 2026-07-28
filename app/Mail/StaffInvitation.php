<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Invitation to a role that cannot be self-served.
 *
 * Carries a link to set a password, never a password. Properties are protected
 * so Mailable::buildViewData() cannot merge them over the payload below, which
 * is what silently broke the volunteer welcome email.
 */
class StaffInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected User $user,
        protected string $acceptUrl,
        protected string $invitedBy,
    ) {
    }

    public function build()
    {
        $data = $this->messagePayload();

        return $this
            ->subject($data['subject'])
            ->view('emails.staff-invitation', $data)
            ->text('emails.staff-invitation-text', $data);
    }

    protected function messagePayload(): array
    {
        $roleLabel = User::staffRoles()[$this->user->role] ?? 'team member';

        return [
            // Layout variables
            'subject'      => 'Your Skills Co-op account is ready to set up',
            'preheader'    => 'Set a password to activate your ' . strtolower($roleLabel) . ' account.',
            'logoUrl'      => 'https://skillscoop.org/email/skills-coop-mark.png',
            'supportEmail' => 'hello@skillscoop.org',
            'footerNote'   => 'You are receiving this because an administrator created an account for you at Skills Co-op. If that is unexpected, tell us and do not use the link.',
            'year'         => date('Y'),

            // Content variables
            'firstName'   => str($this->user->name)->before(' ')->toString(),
            'roleLabel'   => $roleLabel,
            'roleBlurb'   => $this->roleBlurb(),
            'email'       => $this->user->email,
            'acceptUrl'   => $this->acceptUrl,
            'invitedBy'   => $this->invitedBy,
            'expiresDays' => (int) round(config('auth.passwords.invites.expire', 10080) / 1440),
        ];
    }

    /**
     * What the role actually opens up, so the invitee knows what they are
     * being given before they accept it.
     */
    private function roleBlurb(): string
    {
        return match ($this->user->role) {
            'safeguarding' => 'You will be able to read and act on safeguarding concerns raised about learners, and record the decision taken on each one.',
            'coach'        => 'You will be able to see your cohort, track how learners are progressing, and flag anyone you are worried about.',
            'mentor'       => 'You will be able to see the learners matched with you and log each mentoring session.',
            'admin'        => 'You will have full access, including learner records, safeguarding, the risk register and the volunteer roster.',
            default        => 'You will be able to sign in and reach the areas your role allows.',
        };
    }
}
