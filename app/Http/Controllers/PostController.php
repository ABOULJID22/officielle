<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Afficher la liste des articles
     */
    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $query = Post::query()
            ->with(['author', 'category', 'translations' => function ($q) use ($locale) {
                $q->whereIn('locale', [$locale, config('app.fallback_locale')]);
            }, 'category.translations' => function ($q) use ($locale) {
                $q->whereIn('locale', [$locale, config('app.fallback_locale')]);
            }]);

        // Filtre catégorie par slug (localisé)
        if ($request->filled('category')) {
            $slug = $request->query('category');
            $query->whereHas('category', function ($q) use ($slug, $locale) {
                $q->where('slug', $slug)
                  ->orWhereHas('translations', function ($qq) use ($slug, $locale) {
                      $qq->where('locale', $locale)->where('slug', $slug);
                  });
            });
        }

        // Recherche plein texte simple (localisée)
        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s, $locale) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%")
                  ->orWhereHas('translations', function ($qq) use ($s, $locale) {
                      $qq->where('locale', $locale)
                         ->where(function ($qqq) use ($s) {
                             $qqq->where('title', 'like', "%{$s}%")
                                 ->orWhere('content', 'like', "%{$s}%");
                         });
                  });
            });
        }

        // Tri
        $sort = $request->query('sort', 'recent');
        match ($sort) {
          
            default   => $query->orderByDesc('published_at'),
        };

        // Pagination
        $posts = $query->paginate(9)->withQueryString();

        // Catégories pour la barre de filtre (localisées) + tri par nom traduit
        $categories = Category::with(['translations' => function ($q) use ($locale) {
            $q->whereIn('locale', [$locale, config('app.fallback_locale')]);
        }])->get(['id', 'name', 'slug'])
          ->sortBy(fn ($c) => mb_strtolower($c->name));

        return view('pages.blog.index', compact('posts', 'categories'));
    }

    /**
     * Afficher un seul article
     */
    public function show(Post $post): View
    {
        $locale = app()->getLocale();
        $post->load(['author', 'category', 'translations' => function ($q) use ($locale) {
            $q->whereIn('locale', [$locale, config('app.fallback_locale')]);
        }, 'category.translations' => function ($q) use ($locale) {
            $q->whereIn('locale', [$locale, config('app.fallback_locale')]);
        }]);

        $prev = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first(); // précédent [15]

        $next = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first(); // suivant [15]

        $recent = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereKeyNot($post->getKey())
            ->orderByDesc('published_at')
            ->limit(4)
            ->get(['id','slug','title','cover_image','published_at','category_id']); 

        return view('pages.blog.show', compact('post','prev','next','recent'));
    }
}
