<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $locale = app()->getLocale();
        $t = $record->translation($locale) ?: $record->translation(config('app.fallback_locale'));
        if ($t) {
            $record->forceFill([
                'name' => $t->name,
                'slug' => $t->slug,
                'description' => $t->description,
            ])->saveQuietly();
        }
    }
}
