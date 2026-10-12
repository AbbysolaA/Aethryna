<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            // The event banner: the flyer or artwork shown at the top of the
            // panel's page and used as its social share image.
            $table->string('banner_path')->nullable()->after('landing_path');
        });
    }

    public function down(): void
    {
        Schema::table('panel_sessions', function (Blueprint $table) {
            $table->dropColumn('banner_path');
        });
    }
};
