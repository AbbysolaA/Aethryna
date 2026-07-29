<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Marks a referral someone made about themselves.
 *
 * Without this a self-referral is indistinguishable from a third-party one
 * where the two names happen to match, which matters twice over: the notification
 * email should not thank someone for referring themselves, and the consent
 * recorded against the row means something different. Consenting on another
 * person's behalf and consenting for yourself are not the same act, and the
 * record has to say which one happened.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->boolean('is_self_referral')->default(false)->after('referrer_role');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn('is_self_referral');
        });
    }
};
