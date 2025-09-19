<?php

namespace App\Filament\Resources\Commercials;

use App\Filament\Resources\Commercials\Pages\CreateCommercial;
use App\Filament\Resources\Commercials\Pages\EditCommercial;
use App\Filament\Resources\Commercials\Pages\ListCommercials;
use App\Models\Commercial;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class CommercialResource extends Resource
{
    protected static ?string $model = Commercial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?int $navigationSort = 90;

    protected static UnitEnum|string|null $navigationGroup = 'Paramètres';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('name')->label('Nom')->required()->maxLength(191),
            Forms\Components\TextInput::make('contact')->label('Contact')->maxLength(191),
            Forms\Components\Select::make('clients')
                ->label('Pharmacies assignées')
                ->relationship('clients', 'name', function ($query) {
                    return $query->whereHas('roles', fn ($q) => $q->where('name', 'client'));
                })
                ->multiple()
                ->searchable()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact')->label('Contact')->searchable(),
                Tables\Columns\TextColumn::make('clients_count')->counts('clients')->label('Pharmacies'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommercials::route('/'),
            'create' => Pages\CreateCommercial::route('/create'),
            'edit' => Pages\EditCommercial::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user?->isSuperAdmin() ?? false;
    }
}
