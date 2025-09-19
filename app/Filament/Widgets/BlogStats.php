<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $users = User::query()->count();
        $posts = Post::query()->count();
        $contacts = Contact::query()->count();
  
        return [
            Stat::make('Utilisateurs', number_format($users))
                ->icon('heroicon-m-users')
                ->color('indigo'),
            Stat::make('Articles', number_format($posts))
                ->icon('heroicon-m-rectangle-stack')
                ->color('green'),
            Stat::make('Contacts', number_format($contacts))
                ->icon('heroicon-m-envelope')
                ->color('rose'),
        ];
    }

    protected function getColumns(): int|array
    {
        return 1; // stack vertically
    }

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAssistant());
    }
}
