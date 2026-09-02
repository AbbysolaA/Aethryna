<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The blog.
 *
 * Written for search as much as for readers: the keyword research showed the
 * demand is phrased as questions, and a post is the page shape a question
 * deserves. It is also the page other sites can link to, which is where
 * domain authority actually comes from.
 *
 * Body is Markdown, not HTML: the person writing posts should be thinking
 * about sentences, not tags, and Markdown degrades gracefully when pasted
 * into a newsletter or a LinkedIn draft.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // The one-paragraph summary. Does double duty as the card text on
            // the index and the meta description, so writing it once keeps the
            // two from drifting.
            $table->string('standfirst', 300);

            $table->text('body');
            $table->string('author_name')->nullable();

            // Null is a draft. Set on first publish and kept thereafter, so
            // editing a typo does not bump an old post to the top of the
            // index or re-date it in search results.
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
