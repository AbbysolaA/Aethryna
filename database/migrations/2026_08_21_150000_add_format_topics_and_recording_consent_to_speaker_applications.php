<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Three things the Gatherverse form asks that ours did not, now that the real
 * form has been seen rather than approximated.
 *
 * Format preference, because "live panel or pre-recorded" changes what a
 * booking costs the speaker and some excellent people will only do one of
 * them. Topic fit, because a pitch that names its track saves the programming
 * conversation a step. And recording consent, which is the sharp one:
 * sessions are recorded and shared, and finding out a booked speaker never
 * agreed to that after the recording exists is a conversation nobody should
 * have. Gatherverse gets a signed recording agreement at application time;
 * this is the same idea in plain language.
 *
 * What was seen and deliberately not taken: split name fields, organisation
 * logo uploads, continent and country dropdowns, six social profile fields,
 * and sponsorship upsells folded into the speaker form. Every one of those
 * makes the form longer, and long forms cost exactly the first-time speakers
 * the page says it welcomes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('speaker_applications', function (Blueprint $table) {
            // Null means no preference, which is a fine answer.
            $table->string('session_format')->nullable()->after('talk_summary');

            // Which of our tracks the talk speaks to, as a json list. The
            // labels live on the model so a taxonomy rename is one edit.
            $table->json('topic_areas')->nullable()->after('session_format');

            // Stamped, not boolean: when consent was given matters if it is
            // ever questioned. Required at submit, so on new rows it is
            // always set; nullable for the pitches that predate it, which
            // must be asked before any of them is recorded.
            $table->timestamp('recording_consented_at')->nullable()->after('consented_at');
        });
    }

    public function down(): void
    {
        Schema::table('speaker_applications', function (Blueprint $table) {
            $table->dropColumn(['session_format', 'topic_areas', 'recording_consented_at']);
        });
    }
};
