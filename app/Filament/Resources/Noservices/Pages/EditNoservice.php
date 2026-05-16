<?php

namespace App\Filament\Resources\Noservices\Pages;

use App\Filament\Resources\Noservices\NoserviceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNoservice extends EditRecord
{
    protected static string $resource = NoserviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    
   
}
