<?php

namespace App\Console\Commands;

use App\Mail\PromptsAvailable;
use App\Models\BroadcastSend;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Tells the whole registrations bank that a session's prompts are up.
 *
 * Same shape as panels:recap: a command rather than a schedule, because
 * emailing everyone is a decision someone takes, --dry-run to read the
 * list first, and a per-address record so a rerun after banking more
 * contacts reaches only the newcomers.
 */
class SendPromptsEmailCommand extends Command
{
    protected $signature = 'panels:prompts-email
                            {slugs* : The sessions whose prompts to announce, e.g. panel-4 founders-build-live}
                            {--dry-run : Report who would be emailed without sending}';

    protected $description = 'Email the registrations bank that the prompts from the named sessions are up';

    public function handle(): int
    {
        $slugs = $this->argument('slugs');
        $sessions = PanelSession::with('speakers')->whereIn('slug', $slugs)->get()
            ->sortBy(fn ($p) => array_search($p->slug, $slugs, true))->values();

        if ($sessions->count() !== count($slugs)) {
            $this->error('No session with the slug: '.implode(', ', array_diff($slugs, $sessions->pluck('slug')->all())));

            return self::FAILURE;
        }

        $withoutPrompts = $sessions->filter(fn ($p) => $p->prompts()->doesntExist());

        if ($withoutPrompts->isNotEmpty()) {
            $this->error('No prompts added yet for: '.$withoutPrompts->pluck('slug')->implode(', ').'. Add them first; the email points straight to them.');

            return self::FAILURE;
        }

        $broadcast = 'prompts:'.implode('+', $slugs);
        $alreadySent = BroadcastSend::where('broadcast', $broadcast)->pluck('email')->flip();

        $recipients = SessionRegistration::orderByDesc('created_at')
            ->get()
            ->unique(fn ($r) => strtolower($r->email))
            ->reject(fn ($r) => $alreadySent->has(strtolower($r->email)));

        if ($recipients->isEmpty()) {
            $this->info('Everyone in the bank has already had this email.');

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
            BroadcastSend::firstOrCreate([
                'broadcast' => $broadcast,
                'email'     => strtolower($registration->email),
            ]);

            try {
                Mail::to($registration->email)->send(new PromptsAvailable($sessions, $registration->name ?? ''));
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Prompts email failed', [
                    'registration' => $registration->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        $this->info($this->option('dry-run')
            ? $recipients->count().' would be emailed.'
            : 'Sent '.$sent.' email'.($sent === 1 ? '' : 's').'.');

        return self::SUCCESS;
    }
}
