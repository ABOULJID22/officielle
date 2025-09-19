<?php

namespace App\Filament\Resources\Calendar;

use App\Filament\Resources\Calendar\Pages\CreateNote;
use App\Filament\Resources\Calendar\Pages\EditNote;
use App\Filament\Resources\Calendar\Pages\ListNotes;
use App\Models\Note;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static UnitEnum|string|null $navigationGroup = 'Paramètres';
protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('event_id')->label('Événement')->relationship('event', 'title')->searchable()->preload()->required(),
            Forms\Components\Select::make('user_id')->label('Auteur')->relationship('user', 'name')->searchable()->preload()->required(),
            Forms\Components\Textarea::make('content')->label('Contenu')->required()->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Événement')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Auteur')->searchable(),
                Tables\Columns\TextColumn::make('content')->label('Contenu')->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                EditAction::make()->visible(fn() => auth()->user()?->isSuperAdmin() || true),
                DeleteAction::make()->visible(function ($record) {
                    $u = auth()->user();
                    return ($u?->isSuperAdmin()) || ($record->user_id === $u?->id);
                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }
    public static function canAccess(): bool
    {
        $u = auth()->user();
        return $u && $u->isSuperAdmin();
    }


    /* masquer afficher */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
