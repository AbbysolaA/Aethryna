<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            // Once-ever stamps, same discipline as the blog notifications: a
            // reminder that can be re-sent will eventually be re-sent.
            $table->timestamp('reminded_week_at')->nullable()->after('confirmation_sent_at');
            $table->timestamp('reminded_day_at')->nullable()->after('reminded_week_at');
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropColumn(['reminded_week_at', 'reminded_day_at']);
        });
    }
};
