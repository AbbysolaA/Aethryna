<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The kinds of volunteering we offer.
 *
 * Kept as a table rather than a config array because roles come and go with
 * the delivery calendar, and an engagement has to keep pointing at the role it
 * was offered under even after that role stops recruiting.
 *
 * `grants_access` is the users.role value acceptance confers. Mentor grants
 * 'mentor' so an accepted mentor lands in the existing /mentor area with no
 * extra wiring; most roles grant 'volunteer'. Null grants nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->text('description')->nullable();

            $table->enum('grants_access', ['volunteer', 'mentor'])->default('volunteer');

            // Onboarding gates. Surfaced on the engagement timeline so both the
            // volunteer and the admin can see what is outstanding.
            $table->boolean('requires_dbs')->default(false);
            $table->boolean('requires_nda')->default(true);

            // Whether the role is currently recruiting. Does not affect
            // engagements already created under it.
            $table->boolean('is_open')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_roles');
    }
};
