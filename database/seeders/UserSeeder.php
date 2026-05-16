<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $email = env('SEED_ADMIN_EMAIL');
        $plainPassword = env('SEED_ADMIN_PASSWORD');

        if (! $plainPassword) {
            // Generate a secure password for local/dev environments
            if (! app()->environment('production')) {
                $plainPassword = bin2hex(random_bytes(8)); // 16 hex chars
                $this->command?->info("Generated super admin password for {$email}: {$plainPassword}");
            } else {
                // In production, require explicit password via env var to avoid weak defaults
                $this->command?->error('SEED_ADMIN_PASSWORD is required to create super admin in production. Skipping creation.');
                return;
            }
        }

        $superAdmin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => bcrypt($plainPassword),
            ]
        );
        $superAdmin->assignRole('super_admin');

        
    }
}
