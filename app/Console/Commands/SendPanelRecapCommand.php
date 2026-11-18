<?php

namespace App\Console\Commands;

use App\Mail\PanelRecap;
use App\Models\PanelRecapSend;
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
 * first. Sends are recorded per panel and address, so running it twice,
 * or importing more contacts and running it again, only ever reaches
 * people who have not had this panel's recap, and having one panel's
 * recap never blocks the next.
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

        $alreadySent = PanelRecapSend::where('panel_session_id', $panel->id)
            ->pluck('email')
            ->flip();

        // One email per address, newest name wins, nobody twice for the
        // same panel.
        $recipients = SessionRegistration::orderByDesc('created_at')
            ->get()
            ->unique(fn ($r) => strtolower($r->email))
            ->reject(fn ($r) => $alreadySent->has(strtolower($r->email)));

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

            // Recorded before sending: a crash mid-run must not re-email the
            // people already covered.
            PanelRecapSend::firstOrCreate([
                'panel_session_id' => $panel->id,
                'email'            => strtolower($registration->email),
            ]);

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
