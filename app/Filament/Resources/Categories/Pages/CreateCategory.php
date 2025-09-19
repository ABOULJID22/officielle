<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
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
