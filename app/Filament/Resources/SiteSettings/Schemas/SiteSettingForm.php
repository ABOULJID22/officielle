<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;


class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('address')
                    ->default(null),
                FileUpload::make('logo_path')
                    ->default(null)
                    ->image()
                    ->visibility('public')
                    ->disk('public')
                    ->directory('logo'),
                FileUpload::make('favicon_path')
                    ->default(null)
                    ->image()
                    ->visibility('public')
                    ->disk('public')
                    ->directory('favicon_path'),
                TextInput::make('facebook_url')
                    ->url()
                    ->default(null),
                TextInput::make('linkedin_url')
                    ->url()
                    ->default(null),
                TextInput::make('twitter_url')
                    ->url()
                    ->default(null),
                TextInput::make('instagram_url')
                    ->url()
                    ->default(null),
                TextInput::make('youtube_url')
                    ->url()
                    ->default(null),
                FileUpload::make('bgvideo_url')
                    ->label('Background video URL')
                    ->default(null)
                    ->visibility('public')
                    ->disk('public')
                    ->directory('videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg']),
                FileUpload::make('presentationvideo_url')
                    ->label('Presentation video URL')
                    ->default(null)
                    ->visibility('public')
                    ->disk('public')
                    ->directory('videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg']),                TextInput::make('video_id')
                    ->default(null),
            ]);
    }
}
