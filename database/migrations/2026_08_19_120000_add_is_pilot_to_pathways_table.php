<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Which pathways are actually being taught.
 *
 * There are seventeen pathways and four pilot tracks, and until now nothing in
 * the data said which was which. The site marketed four while the assessment
 * scored against all seventeen, so somebody could be matched to a pathway that
 * no cohort runs and nothing anywhere would tell them.
 *
 * A flag rather than a hardcoded list in a template, so the answer changes by
 * editing a record instead of a view — the pilot set will change with Cohort 2
 * and should not need a deploy.
 *
 * Non-pilot pathways are not hidden. They are real directions the assessment
 * can point somebody in, and they keep their page; it just says plainly that
 * the track is not one of the four running now.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->boolean('is_pilot')->default(false)->after('is_active');
            $table->index('is_pilot');
        });
    }

    public function down(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->dropIndex(['is_pilot']);
            $table->dropColumn('is_pilot');
        });
    }
};
