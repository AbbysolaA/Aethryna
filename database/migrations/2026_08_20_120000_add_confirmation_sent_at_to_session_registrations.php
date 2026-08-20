<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Whether the confirmation actually reached the person.
 *
 * Sending is deliberately non-fatal: the registration is already saved by the
 * time mail is attempted, and a mail server having a bad afternoon should not
 * turn into a 500 for someone who arrived from a flyer. The cost of that is
 * that a failure is invisible — the registrant sees a success page, believes
 * they are booked, and nobody finds out they were never told.
 *
 * MAIL_MAILER defaults to `log`, so an environment that has not been pointed at
 * a real mail service will "succeed" at sending every time while writing the
 * message to a file. This column is what makes that state visible and, once the
 * mailer is fixed, what makes it repairable: anyone with a null here is owed an
 * email that was never sent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->timestamp('confirmation_sent_at')->nullable()->after('waitlisted');
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropColumn('confirmation_sent_at');
        });
    }
};
