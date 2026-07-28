<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds 'safeguarding' to the users.role enum.
 *
 * The safeguarding review screen lived inside the admin group, so the only way
 * to let the safeguarding lead read concerns was to make them a full admin,
 * with the user list, content, risk register and volunteer roster attached.
 * That is the wrong trade for someone whose job is one sensitive screen.
 *
 * Admins keep access to safeguarding. This role is additive, not a partition.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'volunteer', 'mentor', 'coach', 'safeguarding', 'admin') DEFAULT 'learner'");
    }

    public function down(): void
    {
        // Move anyone off the value before dropping it, or MySQL silently
        // coerces them to an empty string and they lose all access.
        DB::table('users')->where('role', 'safeguarding')->update(['role' => 'admin']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'volunteer', 'mentor', 'coach', 'admin') DEFAULT 'learner'");
    }
};
