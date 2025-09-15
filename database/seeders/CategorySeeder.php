<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $fixed = [
            ['name' => 'Santé'],
            ['name' => 'Vaccination & Rappels'],
            ['name' => 'Enfant'],
            ['name' => 'Dentaire'],
        ];

        foreach ($fixed as $row) {
            Category::firstOrCreate(
                ['slug' => \Str::slug($row['name'])],
                [
                    'name' => $row['name'],
                    'description' => fake('fr_FR')->sentence(12),
                ]
            );
        }
    }
}
