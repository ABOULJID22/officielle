<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;

class SupportConversations extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';
    protected static ?string $navigationLabel = 'Conversations support';
   // protected static UnitEnum|string|null $navigationGroup = 'Support';
    protected static ?int $navigationSort = 5;
      protected static UnitEnum|string|null $navigationGroup = null;
public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.support');
    }
/* 

   

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = 'Messages de support';

    protected static ?string $modelLabel = 'Message de support';

    protected static UnitEnum|string|null $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.support_clients');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.trade');
    } */
    protected string $view = 'filament.pages.support-conversations';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
    }
}
