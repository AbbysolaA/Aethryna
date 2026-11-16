<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What each speaker is building at this particular session, in their own
 * words. It lives on the pivot, not the speaker: the same person speaks at
 * several panels with different builds, and their bio should stay who they
 * are rather than absorb what they happen to be demoing this month.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_session_speakers', function (Blueprint $table) {
            $table->text('build_note')->nullable()->after('topic');
        });
    }

    public function down(): void
    {
        Schema::table('panel_session_speakers', function (Blueprint $table) {
            $table->dropColumn('build_note');
        });
    }
};
