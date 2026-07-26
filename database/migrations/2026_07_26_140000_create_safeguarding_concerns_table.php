<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('safeguarding_concerns', function (Blueprint $table) {
            $table->id();

            // Who raised it, and about whom.
            $table->foreignId('raised_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('learner_id')->constrained('users')->cascadeOnDelete();
            $table->string('raised_by_role')->nullable(); // mentor | coach | admin at time of raising

            $table->text('concern');
            $table->string('urgency')->default('routine');  // routine | urgent

            // Review workflow, owned by the safeguarding lead.
            $table->string('status')->default('new');       // new | acknowledged | actioned | closed
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('urgency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safeguarding_concerns');
    }
};
