<?php

namespace App\Filament\Resources\Trades\Pages;

use App\Filament\Resources\Trades\TradeOperationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTradeOperation extends CreateRecord
{
    protected static string $resource = TradeOperationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['user_id']) || empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        if (isset($data['products']) && is_array($data['products']) && count($data['products'])) {
            $data['product_id'] = $data['products'][0];
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();
        $productIds = (array) ($data['products'] ?? []);
        if (! empty($productIds)) {
            $record->products()->sync($productIds);
        }
    }
}
