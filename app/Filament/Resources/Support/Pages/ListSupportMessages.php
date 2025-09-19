<?php

namespace App\Filament\Resources\Support\Pages;

use App\Filament\Resources\Support\SupportMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListSupportMessages extends ListRecords
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
