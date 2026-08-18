<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Widens users.role from the original user/admin pair.
 *
 * MODIFY COLUMN ... ENUM is MySQL-only syntax, so every other driver needs a
 * different route to the same outcome. This is not a nicety: without the guard
 * the migration is a hard stop on SQLite, which is what the test suite and a
 * local checkout both run on — `php artisan migrate` and `php artisan test`
 * both died here with a syntax error before reaching anything else.
 *
 * Off MySQL the column becomes a plain string rather than a widened enum.
 * Roles are validated in the application either way, and a CHECK constraint
 * that has to be rebuilt every time a role is added is a migration hazard
 * rather than a safeguard.
 */
return new class extends Migration
{
    public function up(): void
    {
        if ($this->usesMysqlEnum()) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'learner', 'mentor', 'coach', 'admin') DEFAULT 'learner'");

            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('learner')->change();
        });
    }

    public function down(): void
    {
        if ($this->usesMysqlEnum()) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user'");

            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });
    }

    private function usesMysqlEnum(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }
};
