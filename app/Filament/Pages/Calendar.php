<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Calendar extends Page
{ 
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Calendrier';
    protected static ?string $title = 'Calendrier';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.calendar-new';
}
