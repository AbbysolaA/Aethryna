<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Make session registrations worth keeping.
 *
 * The table has existed since May and has never had a row written to it:
 * PageController imported the model and never called it, so every
 * registration went to an email and an EmailOctopus tag and nowhere else.
 * Nothing recorded which panel a person registered for, which is the one
 * thing you need to know when a panel comes round.
 *
 * panel_session_id is nullable and nullOnDelete rather than cascade: a
 * registration is a record that a real person asked to attend, and deleting
 * a panel should not quietly delete the evidence of who wanted to be there.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->foreignId('panel_session_id')
                ->nullable()
                ->after('id')
                ->constrained('panel_sessions')
                ->nullOnDelete();

            $table->boolean('wants_to_speak')->default(false)->after('interest_type');
            $table->text('speaker_topic')->nullable()->after('wants_to_speak');

            // The common lookups: everyone for one panel, and whether a given
            // person has already registered for it.
            $table->index(['panel_session_id', 'created_at']);
            $table->unique(['panel_session_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropUnique(['panel_session_id', 'email']);
            $table->dropIndex(['panel_session_id', 'created_at']);
            $table->dropConstrainedForeignId('panel_session_id');
            $table->dropColumn(['wants_to_speak', 'speaker_topic']);
        });
    }
};
