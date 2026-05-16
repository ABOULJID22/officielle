<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Safety guard: avoid accidentally running all seeders in production.
        if (app()->environment('production') && env('ALLOW_PRODUCTION_SEED') !== '1') {
            $this->command?->error('Seeding in production is disabled by default. Set ALLOW_PRODUCTION_SEED=1 to allow.');
            return;
        }
        // Seeder des utilisateurs
        $this->call([
            // Permissions first, then roles, then assign permissions to roles
            ShieldSeeder::class,
            RoleSeeder::class,
            RolesAndPermissionsSeeder::class,

            // Domain seeders
            CategorySeeder::class,
            SiteSettingSeeder::class,

            // Users before posts to ensure authors exist
            UserSeeder::class,

            // Content
            PostSeeder::class,
            EventSeeder::class,
            BackfillTranslationsSeeder::class,
        ]);
    }
}
