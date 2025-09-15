<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true) 
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state)); // génère slug automatiquement
                    }),

                TextInput::make('slug')
                    ->required(),

                Textarea::make('content')
                    ->default(null)
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
