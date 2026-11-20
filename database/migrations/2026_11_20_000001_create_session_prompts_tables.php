<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The prompts speakers used in their live builds, so viewers can replicate
 * them, and a record of who asked for them.
 *
 * Unlocking takes a name and email; joining the mailing list is a separate,
 * unticked choice, because consent to marketing cannot be the price of the
 * prompts. Every unlock is kept so the team can see who used them, and only
 * the people who opted in join the contacts bank.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_prompts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_session_id')->constrained('panel_sessions')->cascadeOnDelete();
            $table->foreignId('panel_speaker_id')->nullable()->constrained('panel_speakers')->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });

        Schema::create('prompt_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_session_id')->constrained('panel_sessions')->cascadeOnDelete();
            $table->string('name');
            // Lowercased, so one person under two casings is one unlock.
            $table->string('email');
            $table->boolean('opted_in')->default(false);
            $table->timestamps();

            $table->unique(['panel_session_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_unlocks');
        Schema::dropIfExists('session_prompts');
    }
};
