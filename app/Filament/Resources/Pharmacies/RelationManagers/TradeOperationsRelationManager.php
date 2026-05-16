<?php

namespace App\Filament\Resources\Pharmacies\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Exports\TradeOperationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
class TradeOperationsRelationManager extends RelationManager
{
    protected static string $relationship = 'tradeOperations';

    public function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('lab.name')->label('LABO')->searchable(),
                Tables\Columns\TextColumn::make('product_names')->label('PRODUIT(S)')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('challenge_start')
                    ->label('DATE CHALLENGE')
                    ->formatStateUsing(fn ($state, $record) =>
                        '(' . ($record->challenge_start ? \Illuminate\Support\Carbon::parse($record->challenge_start)->format('d-m-Y') : '—')
                        . ' au ' . ($record->challenge_end ? \Illuminate\Support\Carbon::parse($record->challenge_end)->format('d-m-Y') : '—') . ')'
                    )
                    ->wrap(),
                Tables\Columns\TextColumn::make('compensation')->label('Compensation')->formatStateUsing(fn ($record) => $record->compensation_type === 'percent' ? $record->compensation . ' %' : number_format($record->compensation ?? 0, 2, ',', ' ') . ' €'),
                Tables\Columns\IconColumn::make('received')->label('Reçu')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lab_id')->relationship('lab', 'name')->label('LABO'),
                Tables\Filters\SelectFilter::make('product')
                    ->label('Produit')
                    ->options(fn () => \App\Models\Product::orderBy('name')->pluck('name','id'))
                    ->query(function (Builder $q, array $data) {
                        $state = $data['value'] ?? null;
                        if ($state) {
                            $q->where(function ($qq) use ($state) {
                                $qq->where('product_id', $state)
                                   ->orWhereExists(function ($sub) use ($state) {
                                        $sub->from('product_trade_operation as pto')
                                            ->whereColumn('pto.trade_operation_id', 'trade_operations.id')
                                            ->where('pto.product_id', $state);
                                   });
                            });
                        }
                        return $q;
                    }),
                Tables\Filters\TernaryFilter::make('received')->label('Reçu'),
                    Tables\Filters\Filter::make('periode')
                        ->form([
                            \Filament\Forms\Components\DatePicker::make('from')->label('Du'),
                            \Filament\Forms\Components\DatePicker::make('to')->label('Au'),
                        ])
                        ->query(function (Builder $q, array $data) {
                            return $q
                                ->when($data['from'] ?? null, fn ($qq, $from) => $qq->whereDate('challenge_start', '>=', $from))
                                ->when($data['to'] ?? null, fn ($qq, $to) => $qq->whereDate('challenge_end', '<=', $to));
                        }),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Exporter Excel (pharmacie)')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(function () {
                        $owner = $this->getOwnerRecord();
                        return Excel::download(new TradeOperationsExport(auth()->user(), $owner->getKey()), 'trade_' . $owner->id . '_' . now()->format('Ymd_His') . '.xlsx');
                    })
                    ->visible(fn () => auth()->check()),
                Action::make('open_list')
                    ->label('Voir dans la liste Trade')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn () => \App\Filament\Resources\Trades\TradeOperationResource::getUrl('index', ['pharmacy' => $this->getOwnerRecord()->getKey()]))
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Action::make('ouvrir')
                    ->label('Ouvrir')
                    ->url(fn ($record) => \App\Filament\Resources\Trades\TradeOperationResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                Action::make('upload_photos')
                    ->label('Envoyer photos')
                    ->icon('heroicon-m-photo')
                    ->form([
                        \Filament\Forms\Components\FileUpload::make('photos')
                            ->label('Ajouter des photos / justificatifs')
                            ->multiple()
                            ->image()
                            ->directory('trade/photos')
                            ->reorderable()
                            ->visibility('public')
                            ->downloadable()
                            ->appendFiles(),
                    ])
                    ->action(function (array $data, $record) {
                        $existing = (array) ($record->photos ?? []);
                        $new = (array) ($data['photos'] ?? []);
                        $record->update(['photos' => array_values(array_unique(array_merge($existing, $new)))]);
                    })
                    ->visible(fn () => auth()->user()?->isClient() ?? false),
            ])
            ->bulkActions([]);
    }
}
