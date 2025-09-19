<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Category;

class BackfillTranslationsSeeder extends Seeder
{
    public function run(): void
    {
    $default = config('app.locale', 'en');
    $fallback = config('app.fallback_locale', 'en');
    $locales = array_values(array_unique(array_filter([$default, $fallback])));

        // Posts
        Post::with('translations')->chunkById(100, function ($posts) use ($locales) {
            foreach ($posts as $post) {
                foreach ($locales as $loc) {
                    if (! $post->translations()->where('locale', $loc)->exists()) {
                        $post->translations()->create([
                            'locale' => $loc,
                            'title' => $post->getRawOriginal('title'),
                            'slug' => $post->getRawOriginal('slug') ?: Str::slug($post->getRawOriginal('title')),
                            'content' => $post->getRawOriginal('content'),
                        ]);
                    }
                }
            }
        });

        // Categories
        Category::with('translations')->chunkById(100, function ($categories) use ($locales) {
            foreach ($categories as $cat) {
                foreach ($locales as $loc) {
                    if (! $cat->translations()->where('locale', $loc)->exists()) {
                        $cat->translations()->create([
                            'locale' => $loc,
                            'name' => $cat->getRawOriginal('name'),
                            'slug' => $cat->getRawOriginal('slug') ?: Str::slug($cat->getRawOriginal('name')),
                            'description' => $cat->getRawOriginal('description'),
                        ]);
                    }
                }
            }
        });
    }
}
