<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hours a volunteer logged against an engagement.
 *
 * Entries are write-once. The volunteer confirms at the point of submission
 * that the figures are correct and cannot be edited afterwards, because these
 * totals feed funder reporting and a silently editable log is not evidence.
 * Corrections go through an admin, who deletes and re-enters.
 *
 * No unique constraint on (engagement, worked_on): a volunteer can legitimately
 * log two separate blocks of work on the same day.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_engagement_id')->constrained()->cascadeOnDelete();

            $table->date('worked_on');
            $table->decimal('hours', 5, 2);
            $table->string('note')->nullable();

            $table->timestamps();

            $table->index(['volunteer_engagement_id', 'worked_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_hours');
    }
};
