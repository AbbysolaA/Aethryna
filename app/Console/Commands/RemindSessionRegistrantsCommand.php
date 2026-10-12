<?php

namespace App\Console\Commands;

use App\Mail\SessionReminder;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Reminds panel registrants: once a week out, once the day before.
 *
 * Same discipline as blog:notify-subscribers. Each reminder is stamped on
 * the registration before it can repeat, a failure is logged and skipped
 * rather than halting the run, and the command is safe to run as often as
 * the scheduler likes because the stamps, not the schedule, decide who is
 * emailed.
 */
class RemindSessionRegistrantsCommand extends Command
{
    protected $signature = 'sessions:remind {--dry-run : Report who would be emailed without sending}';

    protected $description = 'Email panel registrants a week before and the day before the event';

    public function handle(): int
    {
        $sent = 0;

        $panels = PanelSession::upcoming()
            ->whereNotNull('event_date')
            ->where('event_date', '>', now())
            ->with('speakers')
            ->get();

        foreach ($panels as $panel) {
            // The day stage wins when both windows cover the event, so a
            // panel two days out never gets a stale "one week to go".
            $stage = $panel->event_date->lte(now()->addDays(2)) ? 'day'
                : ($panel->event_date->lte(now()->addDays(7)) ? 'week' : null);

            if (! $stage) {
                continue;
            }

            $registrations = SessionRegistration::where('panel_session_id', $panel->id)
                ->whereNull($stage === 'day' ? 'reminded_day_at' : 'reminded_week_at')
                // The week nudge skips anyone who registered within the last
                // day: their confirmation email just said all of this.
                ->when($stage === 'week', fn ($q) => $q->where('created_at', '<=', now()->subDay()))
                ->get();

            foreach ($registrations as $registration) {
                if ($this->option('dry-run')) {
                    $this->line("[dry run] {$stage}: {$registration->email} for {$panel->tagline}");

                    continue;
                }

                // Stamped before sending: a crash mid-send must not re-email
                // everyone on the next run. One lost reminder is cheaper than
                // a duplicated blast.
                $registration->forceFill([
                    $stage === 'day' ? 'reminded_day_at' : 'reminded_week_at' => now(),
                ])->save();

                try {
                    Mail::to($registration->email)->send(
                        new SessionReminder($panel, $registration->name ?? '', $stage)
                    );

                    $sent++;
                } catch (\Throwable $e) {
                    Log::error('Session reminder failed', [
                        'registration' => $registration->id,
                        'stage'        => $stage,
                        'error'        => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Sent {$sent} reminder(s).");

        return self::SUCCESS;
    }
}
