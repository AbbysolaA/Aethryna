<?php

namespace App\Console\Commands;

use App\Mail\AssessmentResume;
use App\Models\Assessment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Nudge people who started the pathway assessment and stopped, then tidy up
 * the rows that will never be finished.
 *
 * Usage:
 *   php artisan assessments:remind                 # the normal scheduled run
 *   php artisan assessments:remind --dry-run       # show who would be emailed
 *   php artisan assessments:remind --hours=48      # wait longer before nudging
 *
 * Deliberate limits, because this is unsolicited mail to people who did not
 * ask for it:
 *
 *   - One reminder, ever. reminder_sent_at is set whether or not the send
 *     succeeded in a way we can retry, so nobody gets a second.
 *   - Only where an address was volunteered on the assessment itself, and only
 *     after it was made clear at that point that a reminder might follow.
 *   - Only where at least one question was answered. An assessment with no
 *     answers is a bounce off the landing page, and mailing a bounce is spam.
 *   - Not before the wait window, so someone who is mid-assessment over lunch
 *     is not chased while they are still doing it.
 */
class AssessmentRemindCommand extends Command
{
    protected $signature = 'assessments:remind
        {--hours=24 : Leave an unfinished assessment this long before reminding}
        {--within-days=14 : Do not remind about assessments older than this}
        {--stale-days=30 : Mark unfinished assessments older than this as abandoned}
        {--limit=100 : Most reminders to send in one run}
        {--dry-run : Report what would happen without sending or writing}';

    protected $description = 'Email a one-off reminder to people who left the pathway assessment unfinished.';

    public function handle(): int
    {
        $hours  = max(1, (int) $this->option('hours'));
        $within = max(1, (int) $this->option('within-days'));
        $limit  = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');

        $due = Assessment::query()
            ->unfinished()
            ->whereNull('reminder_sent_at')
            ->whereNotNull('contact_email')
            ->where('started_at', '<=', now()->subHours($hours))
            // Bounded at both ends. Chasing someone about something they
            // half-did six weeks ago reads as surveillance, not service — and
            // the first run after this ships would otherwise mail the entire
            // back catalogue of abandons at once.
            ->where('started_at', '>=', now()->subDays($within))
            ->orderBy('started_at')
            ->limit($limit)
            ->get()
            // Answer count lives inside a JSON column, so it cannot be filtered
            // in SQL portably across SQLite and MySQL. The set is already small
            // by this point: it is only ever people who left an address.
            ->filter(fn (Assessment $a) => $a->answeredCount() > 0);

        if ($due->isEmpty()) {
            $this->info('No unfinished assessments are due a reminder.');
        }

        $count = 0;
        $failed = 0;

        foreach ($due as $assessment) {
            $label = $assessment->contact_email . ' (#' . $assessment->id . ', '
                . $assessment->answeredCount() . ' answered, started '
                . $assessment->started_at?->diffForHumans() . ')';

            if ($dryRun) {
                $this->line('  would remind ' . $label);
                $count++;
                continue;
            }

            try {
                // Same reason as in the controller: the link has to exist
                // whether or not the mailable ever gets as far as rendering.
                $assessment->ensureResumeToken();

                Mail::to($assessment->contact_email)
                    ->send(new AssessmentResume($assessment, 'reminder'));

                $assessment->forceFill(['reminder_sent_at' => now()])->save();
                $count++;
                $this->line('  reminded ' . $label);
            } catch (\Throwable $e) {
                // Left unstamped so a later run retries. A permanently bad
                // address gets picked up by the stale sweep below instead of
                // being retried forever.
                $failed++;
                $this->warn('  failed ' . $label . ': ' . $e->getMessage());
                Log::warning('Assessment reminder failed', [
                    'assessment_id' => $assessment->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }

        $stale = $this->markStale($dryRun);

        $this->newLine();
        $this->info($this->summary($dryRun, $count, $failed, $stale));

        return self::SUCCESS;
    }

    /**
     * The closing line.
     *
     * Kept in the past tense for a real run and the conditional for a dry one,
     * rather than one sentence covering both. "0 reminders sent, 0 failed" after
     * a dry run is a report of something that did not happen, and reads as
     * though the run did nothing when in fact it was told not to do anything.
     * A dry run also has no failure count worth printing: nothing was attempted.
     */
    private function summary(bool $dryRun, int $count, int $failed, int $stale): string
    {
        $reminders = $count . ' ' . ($count === 1 ? 'reminder' : 'reminders');
        $abandoned = $stale . ' ' . ($stale === 1 ? 'assessment' : 'assessments');

        if ($dryRun) {
            return sprintf(
                '[dry run] Would send %s and mark %s abandoned. Nothing was sent or saved.',
                $reminders,
                $abandoned
            );
        }

        return sprintf(
            '%s sent, %d failed, %s marked abandoned.',
            $reminders,
            $failed,
            $abandoned
        );
    }

    /**
     * Stamp long-dead rows as abandoned.
     *
     * Nothing is deleted: the answers given before someone stopped are the most
     * useful thing here — they say where the assessment loses people. The stamp
     * only stops those rows sitting in the in-progress count forever and
     * pretending to be live.
     */
    private function markStale(bool $dryRun): int
    {
        $days = max(1, (int) $this->option('stale-days'));

        $query = Assessment::query()
            ->where('status', 'in_progress')
            ->where('started_at', '<=', now()->subDays($days));

        if ($dryRun) {
            return $query->count();
        }

        return $query->update(['status' => 'abandoned']);
    }
}
