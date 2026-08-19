<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A way to say "stop emailing me" that does not require replying and hoping.
 *
 * Kept separate from resume_token deliberately. That token continues an
 * assessment, and an unsubscribe URL travels somewhere a resume link should
 * not: into mail headers, through provider infrastructure, and in front of
 * automated scanners that follow links to check them. Two capabilities, two
 * secrets, so leaking one cannot grant the other.
 *
 * reminders_opted_out_at is its own column rather than a shortcut of setting
 * reminder_sent_at early. Both would stop the reminder, but the shortcut would
 * make admin report a reminder that was never sent, and the record of what we
 * actually sent someone is worth keeping honest.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('unsubscribe_token', 64)->nullable()->unique()->after('resume_token');
            $table->timestamp('reminders_opted_out_at')->nullable()->after('reminder_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropUnique(['unsubscribe_token']);
            $table->dropColumn(['unsubscribe_token', 'reminders_opted_out_at']);
        });
    }
};
