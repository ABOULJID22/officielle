<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
