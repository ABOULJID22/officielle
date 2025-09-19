<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title . '-' . Str::random(6)),
            'content' => $this->faker->paragraphs(rand(3, 8), true),
            'cover_image' => null,
            'author_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'status' => 'draft',
            'published_at' => null,
            'reading_time' => $this->faker->numberBetween(2, 12),
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function scheduled(): self
    {
        return $this->state(fn () => [
            'status' => 'scheduled',
            'published_at' => now()->addDays($this->faker->numberBetween(1, 14)),
        ]);
    }
}
