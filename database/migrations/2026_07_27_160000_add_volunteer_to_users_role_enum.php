<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds 'volunteer' to the users.role enum.
 *
 * Until now the only non-staff contributor role was 'mentor', which meant a
 * project manager, website volunteer or panel facilitator had nowhere to sit.
 * Mentors keep their own value because it gates the existing /mentor area;
 * 'volunteer' is the general contributor with no learner-facing access.
 *
 * Raw ALTER rather than Schema::table because MySQL enums cannot be changed
 * through the fluent builder without doctrine/dbal. Matches the approach in
 * 2026_01_27_132525_update_users_role_enum_for_new_roles.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'volunteer', 'mentor', 'coach', 'admin') DEFAULT 'learner'");
    }

    public function down(): void
    {
        // Demote anyone on the value we are about to drop, otherwise the ALTER
        // silently coerces them to an empty string on MySQL.
        DB::table('users')->where('role', 'volunteer')->update(['role' => 'user']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'mentor', 'coach', 'admin') DEFAULT 'learner'");
    }
};
