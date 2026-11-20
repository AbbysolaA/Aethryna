<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Two additions.
 *
 * session_prompts.note: guidance shown beside a prompt but kept out of what
 * the Copy button copies, such as "Real use" advice or what was different
 * in the live demo. Pasted into an AI tool, those notes would become part
 * of the instruction.
 *
 * broadcast_sends: one row per one-off email and address, so a broadcast
 * such as "the prompts are up" can be rerun after more contacts are banked
 * without anyone receiving it twice. Keyed by a name for the broadcast
 * rather than tied to one panel, because a broadcast can cover several.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_prompts', function (Blueprint $table) {
            $table->text('note')->nullable()->after('title');
        });

        Schema::create('broadcast_sends', function (Blueprint $table) {
            $table->id();
            $table->string('broadcast');
            // Lowercased, so one person under two casings is one send.
            $table->string('email');
            $table->timestamps();

            $table->unique(['broadcast', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_sends');

        Schema::table('session_prompts', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
