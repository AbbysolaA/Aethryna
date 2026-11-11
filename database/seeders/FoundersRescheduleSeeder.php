<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Illuminate\Database\Seeder;

/**
 * Founders Build Live did not happen on 10 November; it moves to
 * Wednesday 18 November 2026, same 6.30pm to 8pm UK slot.
 *
 * Two jobs, deliberately nothing else, so admin edits to the panel and
 * its speakers survive:
 *
 *   1. Move event_date. The page, the Event schema, the .ics download
 *      and the reminder timing all derive from it.
 *
 *   2. Clear the reminder stamps on this panel's registrations. Everyone
 *      registered was already reminded for the 10th, and the stamps are
 *      once-ever; without clearing them nobody would hear about the new
 *      date. Cleared, the daily sessions:remind run sends the "under a
 *      week away" email carrying the 18th, which doubles as the
 *      announcement, and the day-before email follows on the 17th.
 *
 * Re-runnable, though rerunning re-clears stamps only for a date that is
 * still in the future, so it cannot re-email anyone after the event.
 */
class FoundersRescheduleSeeder extends Seeder
{
    public function run(): void
    {
        $founders = PanelSession::where('slug', 'founders-build-live')->first();

        if (! $founders) {
            return;
        }

        $founders->update([
            'event_date' => '2026-11-18 18:30:00',
            'status'     => 'upcoming',
        ]);

        SessionRegistration::where('panel_session_id', $founders->id)->update([
            'reminded_week_at' => null,
            'reminded_day_at'  => null,
        ]);
    }
}
