<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('translations')
                    ->relationship('translations')
                    ->label('Traductions')
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->maxItems(2)
                    ->schema([
                        Select::make('locale')
                            ->label('Langue')
                            ->options([
                                'fr' => 'Français',
                                'en' => 'English',
                            ])
                            ->required()
                            ->disableOptionWhen(function ($value, callable $get) {
                                $items = collect($get('../../translations') ?? [])
                                    ->pluck('locale')
                                    ->filter();
                                $current = $get('locale');
                                return $items->contains($value) && $current !== $value;
                            }),
                        TextInput::make('name')->label('Nom')->required(),
                        TextInput::make('slug')->label('Slug')->required(),
                        Textarea::make('description')->label('Description')->default(null)->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
