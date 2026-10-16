<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            // Where attendees actually watch (StreamYard, YouTube Live, Zoom).
            // Delivered by the day-before reminder and the confirmation email,
            // and used as the calendar entry's URL.
            $table->string('join_url')->nullable()->after('eventbrite_url');
        });
    }

    public function down(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->dropColumn('join_url');
        });
    }
};
