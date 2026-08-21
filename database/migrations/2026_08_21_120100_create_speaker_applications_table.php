<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * People putting themselves forward to speak at our sessions.
 *
 * Speaker interest used to be a checkbox on the event registration form, which
 * captures "I might" and nothing a programme decision can be made from. This
 * holds a real pitch: who they are, what they want to talk about, and where
 * they have spoken before.
 *
 * Its own table rather than a role in the volunteer system, because the
 * questions and the outcome both differ. A speaker is accepted for one talk on
 * the strength of a topic; a volunteer is onboarded into an engagement with
 * agreements and hours. The accepted end of this pipeline is a PanelSpeaker,
 * which is what the session pages already render.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speaker_applications', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organisation')->nullable();
            $table->string('job_title')->nullable();
            $table->string('location')->nullable();

            $table->text('bio');
            $table->string('linkedin_url')->nullable();
            $table->string('website_url')->nullable();

            // The pitch itself.
            $table->string('talk_title');
            $table->text('talk_summary');

            // Free text with links welcome. A first-time speaker with lived
            // experience of our audience can be a better booking than a
            // conference regular, so this is asked for and never required.
            $table->text('prior_speaking')->nullable();
            $table->string('video_url')->nullable();

            // Optional headshot, on the private disk like every other upload.
            // It only becomes public if they are accepted and it is moved into
            // the speakers folder deliberately.
            $table->string('headshot_path')->nullable();
            $table->string('headshot_original_name')->nullable();
            $table->string('headshot_mime')->nullable();
            $table->unsignedInteger('headshot_size')->nullable();

            // new, accepted or declined.
            $table->string('status')->default('new');

            // Set when an application is accepted and a PanelSpeaker minted
            // from it, so the two records stay connected.
            $table->foreignId('panel_speaker_id')
                ->nullable()
                ->constrained('panel_speakers')
                ->nullOnDelete();

            $table->timestamp('consented_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speaker_applications');
    }
};
