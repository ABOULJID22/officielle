<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\NoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Default author to current user
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $url = static::getResource()::getUrl('index');
        if (request()->boolean('in_iframe')) {
            return $url . '?created=1&in_iframe=1&note=1';
        }
        return $url;
    }
}
