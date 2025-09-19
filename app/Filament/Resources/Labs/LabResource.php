<?php

namespace App\Filament\Resources\Labs;

use App\Filament\Resources\Labs\Pages\CreateLab;
use App\Filament\Resources\Labs\Pages\EditLab;
use App\Filament\Resources\Labs\Pages\ListLabs;
use App\Models\Lab;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class LabResource extends Resource
{
    protected static ?string $model = Lab::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static UnitEnum|string|null $navigationGroup = 'Paramètres';
protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Repeater::make('categories')
                    ->label('Catégories du laboratoire')
                    ->relationship('categories')
                    ->minItems(0)
                    ->reorderable()
                    ->collapsible()
                    ->grid(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom de la catégorie')
                            ->required()
                            ->maxLength(191),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListLabs::route('/'),
            'create' => Pages\CreateLab::route('/create'),
            'edit' => Pages\EditLab::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user?->isSuperAdmin() ?? false;
    }
}
