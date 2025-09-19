<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->height(40)
                    ->width(40)
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        $state = $record->avatar_url ?? null;

                        // Fallback to default avatar if empty
                        if (!$state) {
                            return asset('images/avater.png');
                        }

                        // If already a full URL, return as-is
                        if (Str::startsWith($state, ['http://', 'https://'])) {
                            return $state;
                        }

                        // If already a public storage URL (/storage/...), verify existence
                        if (Str::startsWith($state, '/storage/')) {
                            $relative = ltrim(Str::after($state, '/storage/'), '/');
                            return Storage::disk('public')->exists($relative)
                                ? $state
                                : asset('images/avater.png');
                        }

                        // If an absolute local path was stored, convert to storage-relative path
                        if (Str::contains($state, ['storage/app/public', 'storage\\app\\public'])) {
                            $state = 'avatar/' . basename($state);
                        }

                        // Build public URL if file exists, otherwise fallback
                        return Storage::disk('public')->exists($state)
                            ? Storage::disk('public')->url($state)
                            : asset('images/avater.png');
                    }),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('Ville')
                    ->toggleable(),
                TextColumn::make('job_title')
                    ->label('Poste')
                    ->toggleable(),
        
                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                
               
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
