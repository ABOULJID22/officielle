<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
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

                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(),

                        Textarea::make('content')
                            ->label('Contenu')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                FileUpload::make('cover_image')
                    ->default(null)
                    ->image()
                    ->visibility('public')
                    ->disk('public')
                    ->directory('cover_image'),

                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->default(null),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),

                DateTimePicker::make('published_at'),

                TextInput::make('reading_time')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
