<?php

namespace App\Console\Commands;

use App\Mail\PanelRecap;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the watch-it-back recap for a finished panel to the whole
 * registrations bank, once per person.
 *
 * Deliberately a command rather than a schedule: a broadcast to every
 * contact is a decision someone takes, with --dry-run to read the list
 * first. The recap stamp is per registration and checked per email, so
 * running it twice, or importing more contacts and running it again,
 * only ever reaches people who have not had it.
 */
class SendPanelRecapCommand extends Command
{
    protected $signature = 'panels:recap {slug : The finished panel to recap} {--dry-run : Report who would be emailed without sending}';

    protected $description = 'Email the watch-it-back recap for a finished panel to everyone in the registrations bank';

    public function handle(): int
    {
        $panel = PanelSession::with('speakers')->where('slug', $this->argument('slug'))->first();

        if (! $panel) {
            $this->error('No panel with that slug.');

            return self::FAILURE;
        }

        if (! $panel->recording_url) {
            $this->error('That panel has no recording yet. Set it in the panels admin first; the recap leads with it.');

            return self::FAILURE;
        }

        $nextPanel = PanelSession::upcoming()->whereNotNull('event_date')->first();

        // One email per address, newest name wins, nobody twice: the stamp
        // lives on every registration row sharing the address.
        $recipients = SessionRegistration::whereNull('recap_sent_at')
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($r) => strtolower($r->email));

        if ($recipients->isEmpty()) {
            $this->info('Everyone in the bank has already had this recap.');

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($recipients as $registration) {
            if ($this->option('dry-run')) {
                $this->line('[dry run] '.$registration->email.' ('.$registration->name.')');

                continue;
            }

            // Stamped before sending, across every row with this address: a
            // crash mid-run must not re-email the people already covered.
            SessionRegistration::whereRaw('lower(email) = ?', [strtolower($registration->email)])
                ->update(['recap_sent_at' => now()]);

            try {
                Mail::to($registration->email)->send(new PanelRecap($panel, $registration->name ?? '', $nextPanel));
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Panel recap failed', [
                    'registration' => $registration->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        $this->info($this->option('dry-run')
            ? $recipients->count().' would be emailed.'
            : 'Sent '.$sent.' recap'.($sent === 1 ? '' : 's').'.');

        return self::SUCCESS;
    }
}
