<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

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
        // Partager les paramètres du site avec toutes les vues (mise en cache simple en mémoire par requête)
        View::composer('*', function ($view) {
            static $cached; // éviter multiples requêtes Eloquent
            if ($cached === null) {
                $cached = SiteSetting::query()->latest('id')->first();
            }
            $view->with('siteSettings', $cached);
        });
    }
}
