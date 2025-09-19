<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->label('Email address')->email()->required(),
                    TextInput::make('website')->label('Site web')->url(),
                    TextInput::make('first_name')->label('Prénom'),
                    TextInput::make('last_name')->label('Nom'),
                    TextInput::make('job_title')->label('Poste'),
                    TextInput::make('pharmacist_name')->label('Pharmacien responsable'),
                    TextInput::make('registration_number')->label('N° d\'identification / registre'),
                    TextInput::make('phone')->tel()->label('Téléphone'),
                    TextInput::make('phone_2')->tel()->label('Téléphone 2'),
                    TextInput::make('address')->label('Adresse'),
                    TextInput::make('city')->label('Ville'),
                    TextInput::make('postal_code')->label('Code postal'),
                    TextInput::make('country')->label('Pays'),
                    Toggle::make('is_active')->label('Actif')->default(true),
                    FileUpload::make('avatar_url')
                            ->default(null)
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
                            ->visibility('public')
                            ->disk('public')
                            ->directory('avatar')
                            ->preserveFilenames(false),
                    DateTimePicker::make('last_login_at')->label('Dernière connexion'),
                    TextInput::make('password')->password()->revealable()->dehydrateStateUsing(fn ($state) => filled($state) ? $state : null)->dehydrated(fn ($state) => filled($state)),
                    Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->searchable(),
            ]);
    }
}
