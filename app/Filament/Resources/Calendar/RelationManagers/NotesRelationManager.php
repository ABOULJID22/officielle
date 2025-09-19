<?php

namespace App\Filament\Resources\Calendar\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('user_id')->label('Auteur')->relationship('user', 'name')->required()->searchable()->preload(),
            Forms\Components\Textarea::make('content')->label('Contenu')->required()->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Auteur')->searchable(),
                Tables\Columns\TextColumn::make('content')->label('Contenu')->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
