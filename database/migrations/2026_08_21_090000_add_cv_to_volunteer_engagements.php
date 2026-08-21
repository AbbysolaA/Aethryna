<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A CV attached to an application.
 *
 * Volunteers and mentors apply through a form that asks for a paragraph about
 * themselves and a line on availability. That is enough to decide whether to
 * talk to somebody who wants to help at an event, and nowhere near enough for a
 * mentor, where the whole question is what they have done and whether they can
 * hold a conversation with a learner about it. Everyone was retyping their
 * career history into a 2000 character box, or leaving it out.
 *
 * Stored the same way as the onboarding pack: path on a disk the web server
 * does not serve, plus the original filename so a download arrives called what
 * the applicant called it rather than a random string.
 *
 * Kept as columns on the engagement rather than a separate uploads table
 * because it is one optional file per application, and a join table for a
 * one-to-one would buy nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteer_engagements', function (Blueprint $table) {
            $table->string('cv_path')->nullable()->after('experience');
            $table->string('cv_original_name')->nullable()->after('cv_path');
            $table->string('cv_mime')->nullable()->after('cv_original_name');
            $table->unsignedInteger('cv_size')->nullable()->after('cv_mime');
        });
    }

    public function down(): void
    {
        Schema::table('volunteer_engagements', function (Blueprint $table) {
            $table->dropColumn(['cv_path', 'cv_original_name', 'cv_mime', 'cv_size']);
        });
    }
};
