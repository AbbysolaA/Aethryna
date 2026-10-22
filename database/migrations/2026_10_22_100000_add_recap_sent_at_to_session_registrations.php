<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            // Once-ever stamp for the watch-it-back recap, same discipline as
            // the reminders: a broadcast that can repeat eventually will.
            $table->timestamp('recap_sent_at')->nullable()->after('reminded_day_at');
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropColumn('recap_sent_at');
        });
    }
};
