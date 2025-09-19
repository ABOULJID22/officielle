<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
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
