<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Exports\PurchasesExport;
use App\Exports\Templates\PurchaseTemplate;
use App\Imports\PurchasesImport;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPurchases extends ListRecords
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            Actions\Action::make('export')
                ->label('Exporter Excel')
                ->icon('heroicon-m-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\Toggle::make('export_all')
                        ->label('Exporter tout')
                        ->default(true),
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label('Pharmacie (client)')
                        ->options(User::role('client')->pluck('name', 'id'))
                        ->searchable()->preload()
                        ->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\Select::make('lab_id')
                        ->label('Labo')
                        ->relationship('lab', 'name')
                        ->preload()->searchable()
                        ->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\Select::make('status')
                        ->label('Statut')
                        ->options([ 'en_attente' => 'En attente', 'livree' => 'Livrée', 'annulee' => 'Annulée'])
                        ->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\DatePicker::make('last_order_from')->label('Dernière commande (de)')->native(false)->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\DatePicker::make('last_order_to')->label('Dernière commande (à)')->native(false)->visible(fn($get) => !$get('export_all')),
                ])
                ->action(function (array $data) {
                    $user = auth()->user();
                    $fileName = 'achats_' . now()->format('Ymd_His') . '.xlsx';
                    $filters = $data['export_all'] ? [] : [
                        'user_id' => $data['user_id'] ?? null,
                        'lab_id' => $data['lab_id'] ?? null,
                        'status' => $data['status'] ?? null,
                        'last_order_from' => $data['last_order_from'] ?? null,
                        'last_order_to' => $data['last_order_to'] ?? null,
                    ];
                    return Excel::download(new PurchasesExport($user, null, $filters), $fileName);
                })
                ->visible(fn () => auth()->check()),
           /*  Actions\Action::make('import')
                ->label('Importer Excel')
                ->icon('heroicon-m-arrow-up-tray')
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                ->form([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label('Pharmacie (client)')
                        ->options(User::role('client')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Fichier Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'])
                        ->required()
                        ->directory('imports')
                        ->disk('local')
                        ->visibility('private'),
                ])
                ->action(function (array $data) {
                    $path = $data['file'];
                    $fullPath = \Storage::disk('local')->path($path);
                    Excel::import(new PurchasesImport($data['user_id']), $fullPath);
                    \Filament\Notifications\Notification::make()
                        ->title('Import terminé')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('download_purchase_template')
                ->label('Télécharger le modèle')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(fn() => Excel::download(new PurchaseTemplate(), 'modele_achats.xlsx'))
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false), */
        ];
    }
}
