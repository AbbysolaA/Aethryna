<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Users (only if they don't exist)
        if (!User::where('email', 'admin@aethryna.com')->exists()) {
            User::create([
                'name' => 'Aethryna Admin',
                'email' => 'admin@aethryna.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // NOTE: checked email now matches the inserted email so the idempotency
        // guard actually works (previously .com was checked but .org was inserted).
        if (!User::where('email', 'system@aethryna.org')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'system@aethryna.org',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Fabricated regular users and factory-generated learners are dev-only.
        // We do not want fake learner accounts in production. Skip them unless
        // explicitly opted in via SEED_DEMO_USERS=true in the environment.
        if (env('SEED_DEMO_USERS', false) === true) {
            $regularUsers = [
                ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@email.com'],
                ['name' => 'Michael Chen', 'email' => 'michael.chen@email.com'],
                ['name' => 'Amara Okafor', 'email' => 'amara.okafor@email.com'],
                ['name' => 'David Rodriguez', 'email' => 'david.rodriguez@email.com'],
                ['name' => 'Priya Patel', 'email' => 'priya.patel@email.com'],
                ['name' => 'James Wilson', 'email' => 'james.wilson@email.com'],
                ['name' => 'Fatima Al-Zahra', 'email' => 'fatima.alzahra@email.com'],
                ['name' => 'Carlos Mendes', 'email' => 'carlos.mendes@email.com'],
            ];

            foreach ($regularUsers as $userData) {
                if (!User::where('email', $userData['email'])->exists()) {
                    User::create([
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make('password'),
                        'role' => 'user',
                        'email_verified_at' => now(),
                    ]);
                }
            }

            $currentUserCount = User::count();
            if ($currentUserCount < 20) {
                $usersToCreate = 20 - $currentUserCount;
                User::factory($usersToCreate)->create([
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
            }
        }

        $this->command->info('User seeding completed successfully!');
        $this->command->info('Admin login: admin@aethryna.org / password');
        $this->command->info('System admin login: system@aethryna.org / password');
        $this->command->info('All users have password: password');
        $this->command->info('Total users in database: ' . User::count());
    }
}
