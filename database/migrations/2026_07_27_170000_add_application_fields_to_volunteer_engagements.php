<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What someone tells us when they apply through /volunteer/apply.
 *
 * All nullable: an engagement created by an admin extending a direct offer
 * never passes through the application form, so these stay empty for anyone
 * we approached rather than the other way round.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteer_engagements', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('offer_email');

            // Why they want the role, what they can give it, and what they
            // have done before. Read by an admin before extending an offer.
            $table->text('about')->nullable()->after('phone');
            $table->string('availability')->nullable()->after('about');
            $table->text('experience')->nullable()->after('availability');
        });
    }

    public function down(): void
    {
        Schema::table('volunteer_engagements', function (Blueprint $table) {
            $table->dropColumn(['phone', 'about', 'availability', 'experience']);
        });
    }
};
