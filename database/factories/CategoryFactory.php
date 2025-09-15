<?php
// database/factories/CategoryFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Santé',
            'Vaccination & Rappels',
            'Enfant',
            'Dentaire',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake('fr_FR')->sentence(12),
        ];
    }
}
