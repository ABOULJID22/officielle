<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@offitrade.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Assistant
        $assistant = User::firstOrCreate(
            ['email' => 'assistant@offitrade.com'],
            [
                'name' => 'Assistant',
                'password' => bcrypt('password'),
            ]
        );
        $assistant->assignRole('assistant');

        // Client
        $client = User::firstOrCreate(
            ['email' => 'client@offitrade.com'],
            [
                'name' => 'Client',
                'password' => bcrypt('password'),
            ]
        );
        $client->assignRole('client');
    }
}
