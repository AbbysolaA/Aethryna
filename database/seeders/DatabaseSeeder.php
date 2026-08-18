<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users (admins and regular users)
        $this->call([
            UserSeeder::class,
        ]);

        // Seed assessment data
        $this->call([
            PathwaysSeeder::class,
            QuestionsSeeder::class,
        ]);

        // Seed panel sessions and speakers
        $this->call([
            Panel1Seeder::class,
            Panel2Seeder::class,
            Panel3Seeder::class,
            Panel4Seeder::class,
        ]);

        // Seed the volunteer roles offers are extended against. Mentor is one
        // of these, so mentor and volunteer recruitment share a single flow.
        $this->call([
            VolunteerRolesSeeder::class,
        ]);
    }
}
