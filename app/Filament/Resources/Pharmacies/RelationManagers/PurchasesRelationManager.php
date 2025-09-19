<?php

namespace App\Filament\Resources\Pharmacies\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class PurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'purchases';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('lab.name')->label('LABO')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('TYPE'),
                Tables\Columns\TextColumn::make('last_order_date')->label('Dernière')->date(),
                Tables\Columns\TextColumn::make('last_order_value')->label('Valeur')->money('eur', true),
                Tables\Columns\TextColumn::make('next_order_date')->label('Prochaine')->date(),
                Tables\Columns\TextColumn::make('annual_target')->label('Objectif')->money('eur', true),
                Tables\Columns\TextColumn::make('status')->label('Statut')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lab_id')
                    ->label('LABO')
                    ->relationship('lab', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'en_attente' => 'En attente',
                        'livree' => 'Livrée',
                        'annulee' => 'Annulée',
                    ]),
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Du'),
                        Forms\Components\DatePicker::make('to')->label('Au'),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['from'] ?? null, fn ($qq, $from) => $qq->whereDate('last_order_date', '>=', $from))
                            ->when($data['to'] ?? null, fn ($qq, $to) => $qq->whereDate('last_order_date', '<=', $to));
                    }),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Exporter Excel (pharmacie)')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(function () {
                        $owner = $this->getOwnerRecord();
                        return Excel::download(new PurchasesExport(auth()->user(), $owner->getKey()), 'achats_' . $owner->id . '_' . now()->format('Ymd_His') . '.xlsx');
                    })
                    ->visible(fn () => auth()->check()),
                Action::make('open_list')
                    ->label('Voir dans la liste Achats')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn () => \App\Filament\Resources\Purchases\PurchaseResource::getUrl('index', ['pharmacy' => $this->getOwnerRecord()->getKey()]))
                    ->openUrlInNewTab(),
            ])
            ->actions([
               Action::make('ouvrir')
                    ->label('Ouvrir')
                    ->url(fn ($record) => \App\Filament\Resources\Purchases\PurchaseResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
               Action::make('documents')
                    ->label('Documents')
                    ->icon('heroicon-m-paper-clip')
                    ->form([
                        Forms\Components\Placeholder::make('existing')
                            ->label('Fichiers existants')
                            ->content(function ($record) {
                                $files = (array) ($record->attachments ?? []);
                                if (! $files) return '—';
                                $links = collect($files)->map(function ($path) {
                                    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                                    $name = basename($path);
                                    return "<a class=\"text-primary-600 underline\" target=\"_blank\" href=\"{$url}\">{$name}</a>";
                                })->implode('<br>');
                                return new \Illuminate\Support\HtmlString($links);
                            }),
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Ajouter des documents')
                            ->multiple()
                            ->directory('purchases/attachments')
                            ->visibility('public')
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, $record) {
                        $existing = (array) ($record->attachments ?? []);
                        $new = (array) ($data['attachments'] ?? []);
                        $record->update(['attachments' => array_values(array_unique(array_merge($existing, $new)))]);
                    })
                    ->visible(fn () => auth()->user()?->isClient() ?? false),
            ])
            ->bulkActions([]);
    }
}
