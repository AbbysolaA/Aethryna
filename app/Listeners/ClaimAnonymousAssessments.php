<?php

namespace App\Listeners;

use App\Models\Assessment;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Attach assessments taken anonymously to the account they belong to.
 *
 * Almost everyone takes the assessment before they have an account, so without
 * this a learner who signs up afterwards appears never to have taken one, and
 * staff looking at their record see nothing.
 *
 * Two ways in, because neither alone is enough:
 *
 *   the session key — the assessment the visitor is holding right now. Note it
 *     cannot be matched on assessments.session_id: logging in regenerates the
 *     session ID before this event fires, so that column no longer matches.
 *     Session *data* survives the migration, which is why the id is stashed
 *     there when the assessment starts.
 *
 *   the email — catches assessments taken days ago on the same address,
 *     including ones taken in a browser they are not using now.
 */
class ClaimAnonymousAssessments
{
    public function handle(Login|Registered $event): void
    {
        $user = $event->user;

        if (! $user instanceof Authenticatable || ! $user->getAuthIdentifier()) {
            return;
        }

        try {
            $this->claimFromSession($user->getAuthIdentifier());
            $this->claimByEmail($user->getAuthIdentifier(), $user->email ?? null);
        } catch (\Throwable $e) {
            // Signing in must not fail because a bookkeeping tidy did.
            Log::warning('Claiming anonymous assessments failed', [
                'user_id' => $user->getAuthIdentifier(),
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function claimFromSession(int|string $userId): void
    {
        $assessmentId = Session::get('assessment_id');

        if (! $assessmentId) {
            return;
        }

        Assessment::whereKey($assessmentId)
            ->whereNull('user_id')
            ->update(['user_id' => $userId]);
    }

    private function claimByEmail(int|string $userId, ?string $email): void
    {
        if (! $email) {
            return;
        }

        Assessment::whereNull('user_id')
            ->where('contact_email', $email)
            ->update(['user_id' => $userId]);
    }
}
