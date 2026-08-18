<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Allow a panel session with no date yet.
 *
 * The sessions page needs a "coming soon" card between panels so the series
 * does not look finished, and that card exists before a date is fixed. The
 * column was NOT NULL, which forced a placeholder date onto the page — a real
 * date for a real public event that nobody had committed to.
 *
 * Every consumer of event_date is guarded for null: the sessions page, the
 * learner dashboard, and both mail payloads.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->dateTime('event_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Any dateless placeholder has to go before the column can be NOT NULL
        // again, otherwise the change fails on existing rows.
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->dateTime('event_date')->nullable(false)->change();
        });
    }
};
