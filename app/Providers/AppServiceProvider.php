<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('contact-submissions', function (Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        // Partager les paramètres du site avec toutes les vues (mise en cache simple en mémoire par requête)
    View::composer('*', function ($view) {
            static $cached; // éviter multiples requêtes Eloquent
            if ($cached === null) {
                $cached = SiteSetting::query()->latest('id')->first();
            }
            $view->with('siteSettings', $cached);

            $fallback = asset('video/vide1.mp4');
            $raw = $cached?->presentationvideo_url;
            $resolved = $fallback;

            if (filled($raw)) {
                if (Str::startsWith($raw, ['http://', 'https://', '//'])) {
                    $resolved = $raw;
                } elseif (Str::startsWith($raw, ['/'])) {
                    $resolved = asset(ltrim($raw, '/'));
                } elseif (Str::startsWith($raw, ['videos/'])) {
                    $resolved = Storage::disk('public')->url($raw);
                } elseif (Str::startsWith($raw, ['storage/', '/storage/'])) {
                    $resolved = asset(ltrim($raw, '/'));
                } elseif (Str::startsWith($raw, ['video/', 'images/', 'assets/', 'build/'])) {
                    $resolved = asset($raw);
                } else {
                    // Assume storage path (e.g., uploaded via FileUpload on 'public' disk)
                    $resolved = Storage::disk('public')->url($raw);
                }
            }

            $view->with('presentationVideoSrc', $resolved);

            // Background video
            $bgFallback = asset('video/vide2.mp4');
            $bgRaw = $cached?->bgvideo_url;
            $bgResolved = $bgFallback;
            if (filled($bgRaw)) {
                if (Str::startsWith($bgRaw, ['http://', 'https://', '//'])) {
                    $bgResolved = $bgRaw;
                } elseif (Str::startsWith($bgRaw, ['/'])) {
                    $bgResolved = asset(ltrim($bgRaw, '/'));
                } elseif (Str::startsWith($bgRaw, ['videos/'])) {
                    $bgResolved = Storage::disk('public')->url($bgRaw);
                } elseif (Str::startsWith($bgRaw, ['storage/', '/storage/'])) {
                    $bgResolved = asset(ltrim($bgRaw, '/'));
                } elseif (Str::startsWith($bgRaw, ['video/', 'images/', 'assets/', 'build/'])) {
                    $bgResolved = asset($bgRaw);
                } else {
                    $bgResolved = Storage::disk('public')->url($bgRaw);
                }
            }
            $view->with('bgVideoSrc', $bgResolved);
        });

        // Ensure Livewire component is explicitly registered (name -> class)
        if (class_exists(\App\Http\Livewire\SupportConversation::class) && class_exists(Livewire::class)) {
            Livewire::component('support-conversation', \App\Http\Livewire\SupportConversation::class);
            // also register dotted/class style name used in some snapshots
            Livewire::component('app.http.livewire.support-conversation', \App\Http\Livewire\SupportConversation::class);
            // Register fully-qualified class name and alternative dotted variants
            Livewire::component(\App\Http\Livewire\SupportConversation::class, \App\Http\Livewire\SupportConversation::class);
            Livewire::component('App.Http.Livewire.SupportConversation', \App\Http\Livewire\SupportConversation::class);
            Livewire::component('app.http.livewire.supportconversation', \App\Http\Livewire\SupportConversation::class);
        }
    }
}
