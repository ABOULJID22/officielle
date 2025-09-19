<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Téléphone')->searchable(),
                TextColumn::make('bgvideo_url')->label('BG Video')->toggleable(),
                TextColumn::make('presentationvideo_url')->label('Presentation Video')->toggleable(),
                TextColumn::make('video_id')->label('Video ID')->toggleable(),
                TextColumn::make('updated_at')->label('Maj')->since()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
