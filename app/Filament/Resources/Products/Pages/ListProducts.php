<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Imports\ProductsImport;
use App\Exports\Templates\ProductTemplate;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            Actions\Action::make('import')
                ->label(__('filament.actions.import_excel'))
                ->icon('heroicon-m-arrow-up-tray')
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label(__('filament.trade.import.file'))
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'])
                        ->required()
                        ->directory('imports')
                        ->disk('local')
                        ->visibility('private'),
                ])
                ->action(function (array $data) {
                    $path = $data['file'];
                    $fullPath = \Storage::disk('local')->path($path);
                    Excel::import(new ProductsImport(), $fullPath);
                    \Filament\Notifications\Notification::make()
                        ->title(__('filament.trade.import.done'))
                        ->success()
                        ->send();
                }),
            Actions\Action::make('download_product_template')
                ->label('Télécharger le modèle')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(fn() => Excel::download(new ProductTemplate(), 'modele_produits.xlsx'))
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
        ];
    }
}
