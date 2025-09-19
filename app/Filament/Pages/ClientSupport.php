<?php

namespace App\Filament\Pages;

use App\Mail\ContactMessageMail;
use App\Models\Contact;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;
use BackedEnum;
use UnitEnum;

class ClientSupport extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationLabel = 'Demande de Support';
    protected static ?string $title = 'Support';
    protected string $view = 'filament.pages.client-support';
    protected static ?int $navigationSort = 5;
    protected static UnitEnum|string|null $navigationGroup = null;
    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.support');
    }

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->isClient());
    }
    public static function canAccess(): bool
    {
       $user = auth()->user();
        return $user && ($user->isClient());
    }
}
