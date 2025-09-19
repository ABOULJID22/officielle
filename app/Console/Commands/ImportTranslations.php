<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Category;

class ImportTranslations extends Command
{
    protected $signature = 'i18n:import {file : Path to JSON file}';
    protected $description = 'Import FR/EN translations for posts and categories from a JSON file';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (!is_file($path)) {
            $this->error('File not found: '.$path);
            return self::FAILURE;
        }

        $json = json_decode(file_get_contents($path), true);
        if (!is_array($json)) {
            $this->error('Invalid JSON.');
            return self::FAILURE;
        }

        // categories
        foreach (($json['categories'] ?? []) as $c) {
            $baseSlug = $c['base_slug'] ?? null;
            $cat = Category::query()->where('slug', $baseSlug)->first();
            if (!$cat) {
                $this->warn("Category not found: {$baseSlug}");
                continue;
            }
            foreach (['fr','en'] as $loc) {
                if (!isset($c[$loc])) continue;
                $data = $c[$loc];
                $cat->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'name' => $data['name'] ?? $cat->getRawOriginal('name'),
                        'slug' => $data['slug'] ?? ($loc.'-'.$cat->getRawOriginal('slug')),
                        'description' => $data['description'] ?? $cat->getRawOriginal('description'),
                    ]
                );
            }
        }

        // posts
        foreach (($json['posts'] ?? []) as $p) {
            $baseSlug = $p['base_slug'] ?? null;
            $post = Post::query()->where('slug', $baseSlug)->first();
            if (!$post) {
                $this->warn("Post not found: {$baseSlug}");
                continue;
            }
            foreach (['fr','en'] as $loc) {
                if (!isset($p[$loc])) continue;
                $data = $p[$loc];
                $post->translations()->updateOrCreate(
                    ['locale' => $loc],
                    [
                        'title' => $data['title'] ?? $post->getRawOriginal('title'),
                        'slug' => $data['slug'] ?? ($loc.'-'.$post->getRawOriginal('slug')),
                        'content' => $data['content'] ?? $post->getRawOriginal('content'),
                    ]
                );
            }
        }

        $this->info('Import finished.');
        return self::SUCCESS;
    }
}
