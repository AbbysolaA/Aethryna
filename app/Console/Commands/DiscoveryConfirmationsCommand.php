<?php

namespace App\Console\Commands;

use App\Mail\DiscoverySessionRegistered;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Who registered but was never actually told.
 *
 * Sending the confirmation is deliberately non-fatal — the registration is
 * already saved by the time mail is attempted, and a mail server having a bad
 * afternoon should not become a 500 for someone who arrived from a flyer. The
 * price is that a failure is silent: the registrant sees a success page,
 * believes they are booked, and nobody finds out they were never told.
 *
 * The likeliest cause is not a bad afternoon at all. MAIL_MAILER defaults to
 * `log`, so an environment nobody has pointed at a real mail service succeeds
 * at "sending" every time while writing the message to a file. Everything looks
 * healthy and not one email has left the building.
 *
 * Run with no arguments it reports. With --send it sends what is owed, which is
 * both the repair for that window and the quickest way to prove the mailer is
 * really working: --send --only=you@example.com puts a real confirmation in
 * your own inbox.
 */
class DiscoveryConfirmationsCommand extends Command
{
    protected $signature = 'discovery:confirmations
                            {--send : Actually send the missing confirmations}
                            {--only= : Restrict to one email address}
                            {--slug=discovery-session : Which event}';

    protected $description = 'Report or send Discovery Session confirmations that never went out';

    public function handle(): int
    {
        $session = PanelSession::where('slug', $this->option('slug'))->first();

        if (! $session) {
            $this->error('No event with slug '.$this->option('slug').'.');

            return self::FAILURE;
        }

        $this->line('Mailer: <info>'.config('mail.default').'</info>');

        if (config('mail.default') === 'log') {
            $this->warn('MAIL_MAILER is "log", so nothing is being delivered — messages go to storage/logs.');
            $this->line('Set MAIL_MAILER=resend in .env (and RESEND_KEY) before sending anything real.');
            $this->newLine();
        }

        $query = SessionRegistration::where('panel_session_id', $session->id)
            ->awaitingConfirmation()
            ->orderBy('created_at');

        if ($only = $this->option('only')) {
            $query->where('email', $only);
        }

        $owed = $query->get();

        if ($owed->isEmpty()) {
            $this->info('Everyone who registered has had their confirmation.');

            return self::SUCCESS;
        }

        $this->table(
            ['Registered', 'Name', 'Email', 'Status'],
            $owed->map(fn (SessionRegistration $r) => [
                $r->created_at?->format('j M H:i'),
                trim($r->first_name.' '.$r->last_name) ?: $r->name,
                $r->email,
                $r->waitlisted ? 'waiting list' : 'confirmed',
            ])->all()
        );

        if (! $this->option('send')) {
            $this->newLine();
            $this->warn($owed->count().' '.($owed->count() === 1 ? 'person is' : 'people are').' owed a confirmation. Re-run with --send to send them.');

            return self::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($owed as $registration) {
            try {
                Mail::to($registration->email)->send(new DiscoverySessionRegistered($registration, $session));
                $registration->forceFill(['confirmation_sent_at' => now()])->save();
                $sent++;
                $this->line('  sent to '.$registration->email);
            } catch (\Throwable $e) {
                $failed++;
                $this->error('  failed for '.$registration->email.': '.$e->getMessage());
            }
        }

        $this->newLine();
        $this->info($sent.' sent'.($failed ? ', '.$failed.' failed' : '').'.');

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
