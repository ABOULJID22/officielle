<?php

namespace App\Filament\Resources\Labs;

use App\Filament\Resources\Labs\Pages\CreateLab;
use App\Filament\Resources\Labs\Pages\EditLab;
use App\Filament\Resources\Labs\Pages\ListLabs;
use App\Models\Lab;
use BackedEnum;
use Filament\Forms;
use Illuminate\Support\HtmlString;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class LabResource extends Resource
{
    protected static ?string $model = Lab::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static UnitEnum|string|null $navigationGroup = 'Paramètres';
protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament.labs.fields.name'))
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('existing_category_id')
                    ->label(__('filament.labs.fields.copy_existing_category'))
                    ->options(fn () => \App\Models\LabCategory::where(function($q){ $q->where('category_type','purchase')->orWhereNull('category_type'); })->get()->unique('name')->pluck('name','id'))
                    ->placeholder(__('filament.labs.fields.copy_existing_category_placeholder'))
                    ->reactive()
                    ->hidden(fn (?Lab $record, callable $get) => $record ? ($record->type !== 'purchase') : ($get('type') !== 'purchase'))
                    ->afterStateHydrated(function ($state, callable $set, ?Lab $record) {
                        if ($record) {
                            // prefer purchase category if present
                            $cat = $record->categories()->where('category_type', 'purchase')->first()
                                ?? $record->categories()->first();
                            if ($cat) {
                                $global = \App\Models\LabCategory::where('name', $cat->name)->first();
                                if ($global) {
                                    $set('existing_category_id', $global->id);
                                }
                            }
                        }
                    }),
                Forms\Components\Select::make('type')
                    ->label(__('filament.labs.fields.type'))
                    ->options([
                        'trade' => __('filament.labs.types.trade'),
                        'purchase' => __('filament.labs.types.purchase'),
                    ])
                    ->default('trade')
                    ->required()
                    ->reactive(),
                Forms\Components\Placeholder::make('categories_info')
                    ->content(new HtmlString('<div style="color: #b91c1c; border: 1px solid #b91c1c; border-radius: 6px; padding: 12px; background: #fee2e2;">' . e(__('filament.labs.categories_info')) . '</div>'))
                    ->visible(fn (?Lab $record, callable $get) => $record ? ($record->type !== 'purchase') : ($get('type') !== 'purchase'))
                    ->extraAttributes(['style' => 'margin-bottom: 12px;']),

                Forms\Components\Repeater::make('categories')
                    ->label(__('filament.labs.fields.categories_label'))
                    ->relationship('categories')
                    ->minItems(0)
                    ->reorderable()
                    ->collapsible()
                    ->grid(1)
                    ->hidden(fn (?Lab $record, callable $get) => (bool) $get('existing_category_id') || ($record ? ($record->type !== 'purchase') : ($get('type') !== 'purchase')))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.labs.categories.repeater.name'))
                            ->required()
                            ->maxLength(191),
                        Forms\Components\Select::make('category_type')
                            ->label(__('filament.labs.categories.repeater.type'))
                            ->options([
                                'purchase' => __('filament.labs.types.purchase'),
                            ])
                            ->default('purchase')
                            ->disabled()
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('filament.labs.fields.name'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.labs.fields.type'))
                    ->formatStateUsing(fn (?string $state): ?string => $state ? (__('filament.labs.types.' . $state) ?? $state) : null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('filament.labs.fields.type'))
                    ->options([
                        'trade' => __('filament.labs.types.trade'),
                        'purchase' => __('filament.labs.types.purchase'),
                    ])
                    ->placeholder(__('filament.tables.filters.select')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLabs::route('/'),
            'create' => Pages\CreateLab::route('/create'),
            'edit' => Pages\EditLab::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.settings');
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user?->isSuperAdmin() ?? false;
    }
}
