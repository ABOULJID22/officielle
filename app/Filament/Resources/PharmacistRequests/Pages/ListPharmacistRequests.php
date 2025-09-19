<?php

namespace App\Filament\Resources\PharmacistRequests\Pages;

use App\Filament\Resources\PharmacistRequests\PharmacistRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListPharmacistRequests extends ListRecords
{
    protected static string $resource = PharmacistRequestResource::class;

    public function getTitle(): string
    {
        return 'Demandes de participation';
    }
}
