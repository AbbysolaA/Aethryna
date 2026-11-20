<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Full build prompts run long: the WorkProof AI one is about 29,000
 * characters. TEXT caps at 64KB on MySQL, which accented or non-Latin text
 * reaches sooner than the character count suggests, so the column is
 * widened to match the 60,000-character limit the admin form allows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_prompts', function (Blueprint $table) {
            $table->mediumText('body')->change();
        });
    }

    public function down(): void
    {
        Schema::table('session_prompts', function (Blueprint $table) {
            $table->text('body')->change();
        });
    }
};
