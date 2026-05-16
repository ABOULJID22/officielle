<?php

namespace App\Filament\Resources\Labs\Pages;

use App\Filament\Resources\Labs\LabResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\LabCategory;

class CreateLab extends CreateRecord
{
    protected static string $resource = LabResource::class;

    // store the selected existing category id between mutate and afterCreate
    protected ?int $selectedExistingCategoryId = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If an existing category was chosen, inject it into the categories relationship so it will be created/attached
        if (!empty($data['existing_category_id'])) {
            // remember selection for afterCreate fallback
            $this->selectedExistingCategoryId = (int) $data['existing_category_id'];
            $existing = LabCategory::find($data['existing_category_id']);
            if ($existing) {
                $data['categories'] = $data['categories'] ?? [];
                // ensure we don't duplicate by name
                $exists = collect($data['categories'])->contains(fn($c) => isset($c['name']) && $c['name'] === $existing->name);
                if (! $exists) {
                    $data['categories'][] = [
                        'name' => $existing->name,
                        'category_type' => $existing->category_type,
                    ];
                }
            }
        }

        // Remove helper field so it doesn't try to mass assign
        unset($data['existing_category_id']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // If the nested create didn't add the category for some reason, ensure we create a copy here
        if ($this->selectedExistingCategoryId && $this->record) {
            $existing = LabCategory::find($this->selectedExistingCategoryId);
            if ($existing) {
                // Check if the new lab already has a category with the same name
                $has = $this->record->categories()->where('name', $existing->name)->exists();
                if (! $has) {
                    $this->record->categories()->create([
                        'name' => $existing->name,
                        'category_type' => $existing->category_type,
                    ]);
                }
            }
        }
    }
}
