<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            // delivery | safeguarding | financial | data | reputational |
            // legal | people | partnership
            $table->string('category');

            // Inherent score, before mitigation. 1 to 5 each.
            $table->unsignedTinyInteger('likelihood')->default(3);
            $table->unsignedTinyInteger('impact')->default(3);

            $table->text('mitigation')->nullable();

            // Residual score, after the mitigation is in place. Nullable so a
            // risk can be logged before the mitigation is worked out.
            $table->unsignedTinyInteger('residual_likelihood')->nullable();
            $table->unsignedTinyInteger('residual_impact')->nullable();

            $table->string('owner')->nullable();          // named person accountable
            $table->date('review_due')->nullable();
            $table->string('status')->default('open');    // open | mitigating | monitoring | closed

            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('last_reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_reviewed_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('review_due');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
