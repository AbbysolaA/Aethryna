<?php

namespace App\Console\Commands;

use App\Mail\SpeakerPitchInvite;
use App\Models\SessionRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Catches up the people who offered to speak before the follow-up existed.
 *
 * New registrations get the pitch invite automatically; this reaches the
 * backlog who ticked the box and heard nothing. Same shape as panels:recap:
 * a command rather than a schedule because emailing a list is a decision
 * someone takes, --dry-run to read the list first, and a per-address stamp
 * so running it twice reaches nobody twice.
 */
class SpeakerInviteBacklogCommand extends Command
{
    protected $signature = 'registrations:speaker-invite {--dry-run : Report who would be emailed without sending}';

    protected $description = 'Email the speaker pitch invite to registrants who offered to speak and were never followed up';

    public function handle(): int
    {
        // One email per address, newest registration's name and topic win.
        // Excludes anyone already stamped on any row: the automatic send at
        // registration time stamps every row for the address.
        $stamped = SessionRegistration::whereNotNull('speaker_invite_sent_at')
            ->pluck('email')
            ->map(fn ($email) => strtolower($email))
            ->all();

        $recipients = SessionRegistration::where('wants_to_speak', true)
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($r) => strtolower($r->email))
            ->reject(fn ($r) => in_array(strtolower($r->email), $stamped, true));

        if ($recipients->isEmpty()) {
            $this->info('Everyone who offered to speak has already been invited to pitch.');

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($recipients as $registration) {
            if ($this->option('dry-run')) {
                $this->line('[dry run] '.$registration->email.' ('.$registration->name.')'
                    .($registration->speaker_topic ? ' - '.str($registration->speaker_topic)->limit(60) : ''));

                continue;
            }

            // Stamped before sending, across every row with this address: a
            // crash mid-run must not re-email the people already covered.
            SessionRegistration::whereRaw('lower(email) = ?', [strtolower($registration->email)])
                ->update(['speaker_invite_sent_at' => now()]);

            try {
                Mail::to($registration->email)->send(new SpeakerPitchInvite(
                    $registration->name ?? '',
                    $registration->speaker_topic,
                ));
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Speaker pitch invite failed', [
                    'registration' => $registration->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        $this->info($this->option('dry-run')
            ? $recipients->count().' would be emailed.'
            : 'Sent '.$sent.' invite'.($sent === 1 ? '' : 's').'.');

        return self::SUCCESS;
    }
}
