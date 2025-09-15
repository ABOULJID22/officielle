<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\BlogStats;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\CheckUserIsSuperAdmin;
use Filament\Navigation\NavigationGroup;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            /* ->login() */
             ->colors([
                'primary' => Color::hex('#4f6ba3'),
            ])
            ->favicon(asset('favicon.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            //->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                BlogStats::class,
            ])
            ->globalSearch(false)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckUserIsSuperAdmin::class, //middleware 
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->homeUrl('/')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            //->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
             ->navigationGroups([
            NavigationGroup::make()
                 ->label('Blog')
                 ->icon('heroicon-m-rectangle-stack'),
          NavigationGroup::make()
              ->label('Paramètres')
              ->icon('heroicon-m-cog'),
            
        ]);
            
            


            
             
    }



      public static function canAccess(): bool
    {
        $user = auth()->user();

        // Check if a user is authenticated and if they have the 'super_admin' role.
        return $user && $user->isSuperAdmin();
    }

}
