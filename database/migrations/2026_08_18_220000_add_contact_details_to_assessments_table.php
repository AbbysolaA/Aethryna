<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Give an assessment a way to be contacted.
 *
 * Until now the only identity an assessment carried was user_id, which is null
 * for everyone who took it without an account — nearly all of them. That means
 * a completed assessment could not be emailed to the person who sat it, and an
 * abandoned one could not be followed up at all.
 *
 * contact_name / contact_email are captured from the assessment itself rather
 * than from an account, so they are kept separate from the user relationship:
 * someone can register later with a different address, and overwriting one with
 * the other would lose the record of what they actually told us.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('session_id');
            $table->string('contact_email')->nullable()->after('contact_name');

            // Secret in a URL, so it has to be unguessable and single-purpose:
            // it resumes one assessment and grants nothing else.
            $table->string('resume_token', 64)->nullable()->unique()->after('contact_email');

            // Both are "has this already been sent" marks, not analytics. They
            // stop a retry or a second scheduler run emailing someone twice.
            $table->timestamp('results_emailed_at')->nullable()->after('completed_at');
            $table->timestamp('reminder_sent_at')->nullable()->after('results_emailed_at');

            $table->index('contact_email');
            $table->index(['status', 'reminder_sent_at']);
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex(['status', 'reminder_sent_at']);
            $table->dropIndex(['contact_email']);
            $table->dropUnique(['resume_token']);
            $table->dropColumn([
                'contact_name',
                'contact_email',
                'resume_token',
                'results_emailed_at',
                'reminder_sent_at',
            ]);
        });
    }
};
