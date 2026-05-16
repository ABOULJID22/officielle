<?php

namespace App\Filament\Resources\Labs\Pages;

use App\Filament\Resources\Labs\LabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLabs extends ListRecords
{
    protected static string $resource = LabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
