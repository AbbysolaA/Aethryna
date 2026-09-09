<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The state between a writer finishing and an admin agreeing.
 *
 * Content writers cannot publish. What they can do is say "this is ready",
 * which is this timestamp: set, on an unpublished post, it means the post is
 * waiting for an admin to press publish. Cleared when the admin does, or when
 * the writer takes it back to work on it more.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('review_requested_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('review_requested_at');
        });
    }
};
