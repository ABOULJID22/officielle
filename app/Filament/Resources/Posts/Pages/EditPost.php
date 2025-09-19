<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

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
                'title' => $t->title,
                'slug' => $t->slug,
                'content' => $t->content,
            ])->saveQuietly();
        }
    }
}
