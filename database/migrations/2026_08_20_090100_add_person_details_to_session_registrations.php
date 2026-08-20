<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What we need to know about someone coming to a room, rather than a webinar.
 *
 * An online panel needs a name and an email. An in-person event needs a way to
 * reach someone if the venue changes on the morning, and it needs to know
 * whether they have an access requirement before they arrive rather than after.
 *
 * `name` stays and stays populated. The admin list and the CSV export both read
 * it, and splitting the column would break them for the sake of tidiness. It is
 * composed from first and last on save, so there is still only one thing to
 * change when a name changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            // Nullable because every registration taken before now has a single
            // `name` and no way to split it reliably — "Dr Bola John FRSA" does
            // not divide into two fields by looking for a space.
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');

            $table->string('phone')->nullable()->after('email');

            // Which of the groups the programme is for, in their own words.
            // Deliberately separate from interest_type, which answers a
            // different question and is an enum that several drivers disagree
            // about altering.
            $table->string('audience_group')->nullable()->after('interest_type');

            // Free text. Mostly access requirements, sometimes "I am bringing
            // my daughter", occasionally something that needs a phone call.
            $table->text('notes')->nullable()->after('audience_group');

            // When they ticked the box, not just that they did. Consent is a
            // thing that happened at a moment, and under UK GDPR the moment is
            // the part worth being able to evidence.
            $table->timestamp('consented_at')->nullable()->after('notes');

            // Past capacity people are not turned away, they are queued. Free
            // events overbook as a matter of routine and a rejection is a
            // worse outcome than an oversubscribed room.
            $table->boolean('waitlisted')->default(false)->after('consented_at');

            $table->index(['panel_session_id', 'waitlisted']);
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropIndex(['panel_session_id', 'waitlisted']);
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'audience_group',
                'notes',
                'consented_at',
                'waitlisted',
            ]);
        });
    }
};
