<?php

namespace App\Filament\Resources\Trades\Pages;

use App\Filament\Resources\Trades\TradeOperationResource;
use App\Exports\TradeOperationsExport;
use App\Exports\Templates\TradeTemplate;
use App\Imports\TradeOperationsImport;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListTradeOperations extends ListRecords
{
    protected static string $resource = TradeOperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            Actions\Action::make('export')
                ->label(__('filament.actions.export_excel'))
                ->icon('heroicon-m-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\Toggle::make('export_all')->label('Exporter tout')->default(true),
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label(__('filament.trade.import.client'))
                        ->options(User::role('client')->pluck('name', 'id'))
                        ->searchable()->preload()->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\Select::make('lab_id')
                        ->label('Labo')
                        ->relationship('lab', 'name')
                        ->preload()->searchable()->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\Select::make('product_id')
                        ->label('Produit')
                        ->relationship('product', 'name')
                        ->preload()->searchable()->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\DatePicker::make('start_from')->label('Début (de)')->native(false)->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\DatePicker::make('end_to')->label('Fin (à)')->native(false)->visible(fn($get) => !$get('export_all')),
                    \Filament\Forms\Components\Toggle::make('received')->label('Reçu')->visible(fn($get) => !$get('export_all')),
                ])
                ->action(function (array $data) {
                    $user = auth()->user();
                    $fileName = 'trade_' . now()->format('Ymd_His') . '.xlsx';
                    $filters = $data['export_all'] ? [] : [
                        'user_id' => $data['user_id'] ?? null,
                        'lab_id' => $data['lab_id'] ?? null,
                        'product_id' => $data['product_id'] ?? null,
                        'start_from' => $data['start_from'] ?? null,
                        'end_to' => $data['end_to'] ?? null,
                        'received' => $data['received'] ?? null,
                    ];
                    return Excel::download(new TradeOperationsExport($user, null, $filters), $fileName);
                })
                ->visible(fn () => auth()->check()),
          /*   Actions\Action::make('import')
                ->label(__('filament.actions.import_excel'))
                ->icon('heroicon-m-arrow-up-tray')
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                ->form([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label(__('filament.trade.import.client'))
                        ->options(User::role('client')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
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
                    Excel::import(new TradeOperationsImport($data['user_id']), $fullPath);
                    \Filament\Notifications\Notification::make()
                        ->title(__('filament.trade.import.done'))
                        ->success()
                        ->send();
                }),
            Actions\Action::make('download_trade_template')
                ->label('Télécharger le modèle')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(fn() => Excel::download(new TradeTemplate(), 'modele_trade.xlsx'))
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false), */
        ];
    }
}
