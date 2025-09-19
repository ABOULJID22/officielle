<?php

namespace App\Filament\Resources\Pharmacies;

use App\Filament\Resources\Pharmacies\Pages\EditPharmacy;
use App\Filament\Resources\Pharmacies\Pages\ListPharmacies;
use App\Filament\Resources\Pharmacies\RelationManagers\PurchasesRelationManager;
use App\Filament\Resources\Pharmacies\RelationManagers\TradeOperationsRelationManager;
use App\Models\User;
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
class PharmacyResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        $u = auth()->user();
        return $u && method_exists($u, 'isClient') && $u->isClient() ? 'Ma Pharmacie' : 'Pharmacies';
    }

    public static function getNavigationUrl(): string
    {
        $u = auth()->user();
        if ($u && method_exists($u, 'isClient') && $u->isClient()) {
            return static::getUrl('edit', ['record' => $u]);
        }
        return static::getUrl('index');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nom de la pharmacie')->required(),
                Forms\Components\TextInput::make('pharmacist_name')->label('Pharmacien responsable'),
                Forms\Components\TextInput::make('registration_number')->label('Registre / N°')->maxLength(100),
                Forms\Components\TextInput::make('email')->label('Email')
                    ->email()
                    ->disabled(fn () => auth()->user()?->isClient() ?? false),
                Forms\Components\TextInput::make('phone')->label('Téléphone'),
                Forms\Components\TextInput::make('phone_2')->label('Téléphone (2)'),
                Forms\Components\TextInput::make('website')->label('Site web'),
                Forms\Components\TextInput::make('address')->label('Adresse')->columnSpanFull(),
                Forms\Components\TextInput::make('city')->label('Ville'),
                Forms\Components\TextInput::make('postal_code')->label('Code postal'),
                Forms\Components\TextInput::make('country')->label('Pays')->default('Maroc'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Pharmacie')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->label('Ville')->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Téléphone'),
                Tables\Columns\TextColumn::make('email')->label('Email')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->date()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                EditAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->button()
                    ->color('danger'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                  DeleteBulkAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PurchasesRelationManager::class,
            TradeOperationsRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'city', 'phone'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPharmacies::route('/'),
            'edit' => Pages\EditPharmacy::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $u = auth()->user();
        return $u && ($u->isSuperAdmin() ?? false);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canViewAny(): bool
    {
        $u = auth()->user();
        return $u && ($u->isSuperAdmin() || $u->isAssistant() || $u->isClient());
    }

    public static function getEloquentQuery(): Builder
    {
        $q = parent::getEloquentQuery()->whereHas('roles', fn ($r) => $r->where('name', User::ROLE_CLIENT));
        $u = auth()->user();

        if ($u && $u->isClient()) {
            return $q->where('id', $u->id);
        }

        if ($u && $u->isAssistant()) {
            return $q->whereIn('id', function ($sub) use ($u) {
                $sub->from('commercial_user as cu')
                    ->select('cu.user_id')
                    ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                    ->where('c.user_id', $u->id);
            });
        }

        return $q;
    }

    public static function canEdit($record): bool
    {
        $u = auth()->user();
        return $u && ($u->isSuperAdmin() || ($u->isClient() && (int) $record->getKey() === (int) $u->getKey()));
    }
    
}
