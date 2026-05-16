<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use UnitEnum; 

class SupportConversations extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';
    // Hide from navigation; we'll link to this page from the Support Messages list instead


protected string $view = 'filament.pages.support-conversations';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
      

    
    
}
