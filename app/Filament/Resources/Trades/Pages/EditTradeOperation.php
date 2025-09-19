<?php

namespace App\Filament\Resources\Trades\Pages;

use App\Filament\Resources\Trades\TradeOperationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTradeOperation extends EditRecord
{
    protected static string $resource = TradeOperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord()->loadMissing('products');
        $data['products'] = $record->products->pluck('id')->all();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['products']) && is_array($data['products']) && count($data['products'])) {
            $data['product_id'] = $data['products'][0];
        } else {
            $data['product_id'] = null;
        }
        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();
        $productIds = (array) ($data['products'] ?? []);
        $record->products()->sync($productIds);
    }
}
