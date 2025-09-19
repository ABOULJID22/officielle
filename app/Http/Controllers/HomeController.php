<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get(['id','slug','title','content','cover_image','category_id','published_at']);

        $siteSettings = SiteSetting::query()->latest('id')->first();

        $fallback = asset('video/vide1.mp4');
        $raw = $siteSettings?->presentationvideo_url;
        $resolved = $fallback;
        if (filled($raw)) {
            if (Str::startsWith($raw, ['http://', 'https://', '//'])) {
                $resolved = $raw;
            } elseif (Str::startsWith($raw, ['/'])) {
                $resolved = asset(ltrim($raw, '/'));
            } elseif (Str::startsWith($raw, ['video/', 'videos/', 'images/', 'assets/', 'build/', 'storage/'])) {
                $resolved = asset($raw);
            } else {
                $resolved = Storage::disk('public')->url($raw);
            }
        }

        return view('welcome', [
            'posts' => $posts,
            'siteSettings' => $siteSettings,
            'presentationVideoSrc' => $resolved,
        ]);
    }
}
