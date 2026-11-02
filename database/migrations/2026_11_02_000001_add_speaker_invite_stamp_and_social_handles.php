<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Two small columns for the speaker pipeline.
 *
 * speaker_invite_sent_at: the registration form has an "I'd be open to
 * speaking" tick, but nothing ever followed it up, so the offer sat in the
 * admin as a note nobody acted on. The follow-up email that now goes out
 * needs a stamp so nobody is invited twice, however many panels they
 * register for.
 *
 * social_handles: when a panel is announced we tag speakers in the posts.
 * LinkedIn was the only handle the pitch collected, so tagging on Instagram
 * or TikTok meant asking each speaker individually.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->timestamp('speaker_invite_sent_at')->nullable()->after('wants_to_speak');
        });

        Schema::table('speaker_applications', function (Blueprint $table) {
            $table->string('social_handles', 500)->nullable()->after('website_url');
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropColumn('speaker_invite_sent_at');
        });

        Schema::table('speaker_applications', function (Blueprint $table) {
            $table->dropColumn('social_handles');
        });
    }
};
