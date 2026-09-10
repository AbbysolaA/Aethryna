<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The blog's own subscriber list.
 *
 * EmailOctopus keeps a copy for marketing, but its API takes contacts in and
 * gives no way to send a campaign out, so the "new post lands in your inbox"
 * promise has to be kept by the site itself. That needs the list here: one
 * row per address, with the token its unsubscribe links are signed with.
 *
 * Unsubscribing stamps a timestamp rather than deleting the row, so an
 * address that unsubscribes stays unsubscribed even if a bot or an old form
 * resubmits it, until the person themselves signs up again.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('token', 64)->unique();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            // Stamped when subscribers have been told about the post. Null on
            // a published post is what the notifier command looks for.
            $table->timestamp('subscribers_notified_at')->nullable()->after('review_requested_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_subscribers');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('subscribers_notified_at');
        });
    }
};
