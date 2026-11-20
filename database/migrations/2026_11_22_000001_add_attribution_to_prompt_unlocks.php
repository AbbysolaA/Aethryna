<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Where each prompt unlock came from, so the team can see which platform's
 * posts actually bring people to the prompts.
 *
 * The utm columns match session_registrations and come from tagged links.
 * referrer_host is the fallback for the many shares that carry no tags: a
 * click from LinkedIn or Instagram usually still names the site it came
 * from, even when the link itself is bare.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prompt_unlocks', function (Blueprint $table) {
            $table->string('utm_source', 100)->nullable()->after('opted_in');
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 100)->nullable()->after('utm_medium');
            $table->string('referrer_host', 100)->nullable()->after('utm_campaign');
        });
    }

    public function down(): void
    {
        Schema::table('prompt_unlocks', function (Blueprint $table) {
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_host']);
        });
    }
};
