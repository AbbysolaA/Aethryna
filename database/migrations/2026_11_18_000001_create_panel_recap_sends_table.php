<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Who has had the recap for which panel.
 *
 * The first version stamped a single recap_sent_at on the registration,
 * which recorded that someone had a recap but not which one. After the
 * first broadcast everyone would have counted as done for every panel
 * after it, and the next recap would have reached nobody. One row per
 * panel and address instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panel_recap_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_session_id')->constrained('panel_sessions')->cascadeOnDelete();
            // Lowercased, so one person under two casings is one send.
            $table->string('email');
            $table->timestamps();

            $table->unique(['panel_session_id', 'email']);
        });

        // The only recap the old column could have recorded is Panel 4's,
        // the one broadcast it was built for.
        $panel4 = DB::table('panel_sessions')->where('slug', 'panel-4')->value('id');

        if ($panel4) {
            $emails = DB::table('session_registrations')
                ->whereNotNull('recap_sent_at')
                ->pluck('email')
                ->map(fn ($email) => strtolower($email))
                ->unique();

            foreach ($emails as $email) {
                DB::table('panel_recap_sends')->insertOrIgnore([
                    'panel_session_id' => $panel4,
                    'email'            => $email,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('panel_recap_sends');
    }
};
