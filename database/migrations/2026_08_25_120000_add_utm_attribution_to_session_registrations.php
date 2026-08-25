<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Which campaign, if any, brought this person here.
 *
 * The site runs no advertising pixels, by policy: the cookie pages promise
 * visitors there is no Facebook Pixel and no retargeting, and that promise is
 * what lets the site run without a consent banner. These columns are the
 * pixel-free alternative. Ad URLs carry utm_* parameters, the middleware notes
 * them when somebody lands, and the values are written here when that somebody
 * registers. First-party, server-side, and exact where a pixel's modelled
 * conversion count is an estimate.
 *
 * Kept separate from referral_source, which is the person's own answer to
 * "how did you hear about us". Somebody can truthfully say "word of mouth"
 * after clicking the ad their friend sent them; both facts are worth keeping.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->string('utm_source', 100)->nullable()->after('referral_source');
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 100)->nullable()->after('utm_medium');
        });
    }

    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign']);
        });
    }
};
