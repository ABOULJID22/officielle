<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('avatar')
                    ->label('Avatar')
                    ->circular(),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('first_name')->label('Prénom')->placeholder('-'),
                TextEntry::make('last_name')->label('Nom')->placeholder('-'),
                TextEntry::make('phone')->label('Téléphone')->placeholder('-'),
                TextEntry::make('phone_2')->label('Téléphone 2')->placeholder('-'),
                TextEntry::make('address')->label('Adresse')->placeholder('-'),
                TextEntry::make('city')->label('Ville')->placeholder('-'),
                TextEntry::make('postal_code')->label('Code postal')->placeholder('-'),
                TextEntry::make('country')->label('Pays')->placeholder('-'),
                TextEntry::make('job_title')->label('Poste')->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('last_login_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
