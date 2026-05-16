<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
        ];
    }
}
