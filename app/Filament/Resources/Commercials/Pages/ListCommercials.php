<?php

namespace App\Filament\Resources\Commercials\Pages;

use App\Filament\Resources\Commercials\CommercialResource;
use App\Imports\CommercialsImport;
use App\Exports\Templates\CommercialTemplate;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommercials extends ListRecords
{
    protected static string $resource = CommercialResource::class;

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
                    Excel::import(new CommercialsImport(), $fullPath);
                    \Filament\Notifications\Notification::make()
                        ->title(__('filament.trade.import.done'))
                        ->success()
                        ->send();
                }),
            Actions\Action::make('download_commercial_template')
                ->label('Télécharger le modèle')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(fn() => Excel::download(new CommercialTemplate(), 'modele_commerciaux.xlsx'))
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
        ];
    }
}
