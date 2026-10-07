<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speaker_application_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_application_id')->constrained()->cascadeOnDelete();
            // Who pressed send. Nullable so deleting a staff account does not
            // delete the record of what was said to a speaker.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject', 150);
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speaker_application_replies');
    }
};
