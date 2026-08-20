<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Everything an in-person event needs that an online panel does not.
 *
 * The panel_sessions table was built for virtual panels: a date, a duration,
 * a format and a ticket link. An event people physically travel to has to
 * answer a different set of questions before anyone will commit an afternoon
 * to it — where is it, can I get in, is it full — and those answers belong in
 * the record rather than hard-coded into one Blade.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->string('venue_name')->nullable()->after('format');
            $table->string('venue_address')->nullable()->after('venue_name');

            // Written out rather than a boolean. "Step-free access throughout,
            // including toilets" is the sentence that decides whether someone
            // comes; an accessible=true flag tells them nothing they can act on.
            $table->text('accessibility_note')->nullable()->after('venue_address');

            // A room holds what it holds. Null means no limit, which is the
            // right default for anything online.
            $table->unsignedInteger('capacity')->nullable()->after('accessibility_note');

            // What happens between arriving and leaving. Knowing the shape of
            // the afternoon in advance is most of what makes an unfamiliar room
            // approachable to someone who has been let down by institutions.
            $table->json('itinerary')->nullable()->after('capacity');

            // Where this event's canonical page lives when it has one of its
            // own. Without it the only way to send /sessions/{slug} to a
            // bespoke page is to hard-code a slug in the controller.
            $table->string('landing_path')->nullable()->after('itinerary');
        });
    }

    public function down(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'venue_name',
                'venue_address',
                'accessibility_note',
                'capacity',
                'itinerary',
                'landing_path',
            ]);
        });
    }
};
