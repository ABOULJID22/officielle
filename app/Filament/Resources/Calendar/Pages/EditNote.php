<?php

namespace App\Filament\Resources\Calendar\Pages;

use App\Filament\Resources\Calendar\NoteResource;
use Filament\Resources\Pages\EditRecord;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();
        $u = auth()->user();
        if (!($u?->isSuperAdmin()) && $this->record?->user_id !== $u?->id) {
            abort(403);
        }
    }
}
