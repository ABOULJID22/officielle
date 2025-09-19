<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;
}
