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
        $query = Post::query()
            ->with(['author', 'category']); // eager loading relations

        // Filtre catégorie par slug
        if ($request->filled('category')) {
            $slug = $request->query('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
        }

        // Recherche plein texte simple
        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        // Tri
        $sort = $request->query('sort', 'recent');
        match ($sort) {
          
            default   => $query->orderByDesc('published_at'),
        };

        // Pagination
        $posts = $query->paginate(9)->withQueryString();

        // Catégories pour la barre de filtre
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('pages.blog.index', compact('posts', 'categories'));
    }

    /**
     * Afficher un seul article
     */
    public function show(Post $post): View
    {
        $post->load(['author', 'category']); // relations pour la vue [15]

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
