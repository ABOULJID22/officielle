<?php

namespace App\Filament\Resources\Noservices;

use App\Filament\Resources\Noservices\Pages\CreateNoservice;
use App\Filament\Resources\Noservices\Pages\EditNoservice;
use App\Filament\Resources\Noservices\Pages\ListNoservices;
use App\Filament\Resources\Noservices\Schemas\NoserviceForm;
use App\Filament\Resources\Noservices\Tables\NoservicesTable;
use App\Models\Noservice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NoserviceResource extends Resource
{
    protected static ?string $model = Noservice::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-puzzle-piece';

    // Use the French title attribute as the record title (falls back to English via accessors)
    protected static ?string $recordTitleAttribute = 'titre';
    protected static ?int $navigationSort = 90;
    public static function form(Schema $schema): Schema
    {
        return NoserviceForm::configure($schema);
    }
// Translation-ready navigation label
     // Translation-ready navigation label
    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.noservices');
    }

    public static function getLabel(): string
    {
        return __('filament.resources.noservices.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.resources.noservices.plural');
    }
    public static function table(Table $table): Table
    {
        return NoservicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNoservices::route('/'),
            'create' => CreateNoservice::route('/create'),
            'edit' => EditNoservice::route('/{record}/edit'),
        ];
    }
}
