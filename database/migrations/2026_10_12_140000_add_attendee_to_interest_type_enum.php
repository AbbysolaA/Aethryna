<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds 'attendee' to the session_registrations.interest_type enum.
 *
 * Same trap as users.role before it: the option shipped in code and the
 * first real registration got "Data truncated for column 'interest_type'",
 * because the production column is a MySQL enum and an enum takes only the
 * values it was told about. The test suite runs on SQLite, where the same
 * column is a plain string, which is why every attendee test passed while
 * the live form threw a 500.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! $this->usesMysqlEnum()) {
            // Off MySQL the column is already a plain string, so there is no
            // enum to widen.
            return;
        }

        DB::statement("ALTER TABLE session_registrations MODIFY COLUMN interest_type ENUM('attendee', 'learner', 'mentor', 'partner', 'curious') NOT NULL");
    }

    public function down(): void
    {
        // Move anyone off the value before dropping it, or MySQL silently
        // coerces them to an empty string.
        DB::table('session_registrations')->where('interest_type', 'attendee')->update(['interest_type' => 'curious']);

        if (! $this->usesMysqlEnum()) {
            return;
        }

        DB::statement("ALTER TABLE session_registrations MODIFY COLUMN interest_type ENUM('learner', 'mentor', 'partner', 'curious') NOT NULL");
    }

    private function usesMysqlEnum(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }
};
