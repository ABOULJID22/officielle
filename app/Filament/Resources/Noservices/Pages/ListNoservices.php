<?php

namespace App\Filament\Resources\Noservices\Pages;

use App\Filament\Resources\Noservices\NoserviceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNoservices extends ListRecords
{
    protected static string $resource = NoserviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
}
