<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds 'editor' to the users.role enum.
 *
 * The role shipped in code without this, and MySQL answered the first real
 * invitation with "Data truncated for column 'role'": the production column
 * is an enum, and an enum takes only the values it was told about. The test
 * suite runs on SQLite, where the same column is a plain string (see
 * 2026_01_27_132525), which is why every editor test passed while the live
 * site could not create one.
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

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'volunteer', 'mentor', 'coach', 'safeguarding', 'editor', 'admin') DEFAULT 'learner'");
    }

    public function down(): void
    {
        // Move anyone off the value before dropping it, or MySQL silently
        // coerces them to an empty string and they lose all access.
        DB::table('users')->where('role', 'editor')->update(['role' => 'learner']);

        if (! $this->usesMysqlEnum()) {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'volunteer', 'mentor', 'coach', 'safeguarding', 'admin') DEFAULT 'learner'");
    }

    private function usesMysqlEnum(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }
};
