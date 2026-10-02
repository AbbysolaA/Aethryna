<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The prose the five-track master handoff writes for each founding track
 * page: a one-line hero promise and three narrative sections. Nullable
 * throughout, because the twelve non-pilot pathways keep rendering exactly
 * as they do now with none of these set.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->string('hero_promise')->nullable()->after('description');
            $table->text('learn_text')->nullable()->after('recommended_for');
            $table->text('make_text')->nullable()->after('learn_text');
            $table->text('leads_text')->nullable()->after('make_text');
        });
    }

    public function down(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->dropColumn(['hero_promise', 'learn_text', 'make_text', 'leads_text']);
        });
    }
};
