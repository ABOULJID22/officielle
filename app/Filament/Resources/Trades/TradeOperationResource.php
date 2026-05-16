<?php

namespace App\Filament\Resources\Trades;

use App\Filament\Resources\Trades\Pages\CreateTradeOperation;
use App\Filament\Resources\Trades\Pages\EditTradeOperation;
use App\Filament\Resources\Trades\Pages\ListTradeOperations;
use App\Models\Lab;
use App\Models\Product;
use App\Models\User;
use App\Models\TradeOperation;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

use UnitEnum;

class TradeOperationResource extends Resource
{
    protected static ?string $model = TradeOperation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    //protected static UnitEnum|string|null $navigationGroup = null;
        protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.trade');
    }

    /* public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.trade');
    } */
    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('filament.trade.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.trade.models');
    }

    public static function form(Schema $schema): Schema
    {
        // Ensure public directories exist so FileUpload can store files there
        try {
            Storage::disk('public')->makeDirectory('trade/contracts');
            Storage::disk('public')->makeDirectory('trade/photos');
            Storage::disk('public')->makeDirectory('trade/attachments');
        } catch (\Throwable $e) {
            // ignore - if storage isn't writable or disk misconfigured, upload will fail later
        }

        return $schema
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('filament.trade.fields.pharmacy'))
                     ->relationship('user', 'name', function ($query) {
                                // On limite uniquement aux utilisateurs avec le rôle "client"
                                $query->whereHas('roles', fn ($r) => $r->where('name', 'client'));
                            })
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->pharmacy_name ?: $record->name) // Affiche pharmacy_name si dispo, sinon name
                    ->preload()
                    ->default(fn () => request()->integer('pharmacy'))
                    ->visible(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant())
                    ->required(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant()),
                Forms\Components\Select::make('lab_id')
                    ->label(__('filament.trade.fields.lab'))
                    // Allow selecting any existing lab that is either type 'trade' or 'purchase'
                    ->relationship('lab', 'name', function ($query) {
                        $query->whereIn('type', ['trade', 'purchase']);
                    })
                    ->searchable()->preload()->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.nav.resources.labs'))->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $lab = \App\Models\Lab::firstOrCreate(['name' => $data['name']], ['name' => $data['name'], 'type' => 'trade']);
                        return $lab->id;
                    })
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('product_id', null)),
                Forms\Components\Select::make('products')
                    ->label(__('filament.trade.fields.products'))
                    ->multiple()
                    ->options(function (callable $get) {
                        $labId = $get('lab_id');
                        $q = \App\Models\Product::query();
                        if ($labId) {
                            $q->where('lab_id', $labId);
                        }
                        return $q->orderBy('name')->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.nav.resources.products'))->required(),
                    ])
                    ->createOptionUsing(function (array $data, $get = null) {
                        $labId = $get ? $get('lab_id') : null;
                        $product = \App\Models\Product::firstOrCreate(['lab_id' => $labId, 'name' => $data['name']], ['lab_id' => $labId, 'name' => $data['name']]);
                        return $product->id;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $first = is_array($state) && count($state) ? $state[0] : null;
                        $set('product_id', $first);
                    }),
                Forms\Components\DatePicker::make('challenge_start')
                    ->label(__('filament.trade.fields.date_challenge'))
                    ->placeholder(__('filament.trade.placeholders.start')),
                Forms\Components\DatePicker::make('challenge_end')
                    ->hiddenLabel()
                    ->placeholder(__('filament.trade.placeholders.end')),
                Forms\Components\Hidden::make('product_id'),
                Forms\Components\TextInput::make('compensation')->label(__('filament.trade.fields.compensation'))->numeric()->step('0.01')->minValue(0),
                Forms\Components\Select::make('compensation_type')
                    ->label(__('filament.trade.fields.compensation_type'))
                    ->options([
                        'amount' => __('filament.trade.enums.compensation_type.amount'),
                        'percent' => __('filament.trade.enums.compensation_type.percent'),
                    ])
                    ->default('amount')
                    ->native(false),
                Forms\Components\DatePicker::make('sent_at')->label(__('filament.trade.fields.sent_at')),
                Forms\Components\TextInput::make('via')->label(__('filament.trade.fields.via'))->maxLength(100),
                /* Forms\Components\FileUpload::make('contract_path')
                    ->label(__('filament.trade.fields.contract_file'))
                    ->directory('trade/contracts')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->visibility('public'), */
                Forms\Components\Toggle::make('received')->label(__('filament.trade.fields.received')),
                
                Forms\Components\FileUpload::make('attachments')
                    ->label(__('filament.trade.fields.attachments'))
                    ->multiple()
                    ->directory('trade/attachments')
                    ->disk('public')
                    ->dehydrateStateUsing(function ($state, callable $get, ?TradeOperation $record) {
                        // Add metadata only for attachments newly added in this submit; keep legacy entries unchanged.
                        $user = auth()?->user();
                        $uploaderId = $user?->id;
                        $uploaderName = $user ? ($user->pharmacy_name ?? $user->name) : null;
                        $now = Carbon::now()->toDateTimeString();

                        $existing = Arr::wrap($record?->attachments ?? []);
                        $existingPaths = collect($existing)
                            ->map(function ($e) {
                                if (is_string($e)) return $e;
                                if (is_array($e) || $e instanceof \ArrayAccess) return $e['path'] ?? ($e[0] ?? null);
                                return null;
                            })
                            ->filter()->values()->all();
                        $byPath = [];
                        foreach ($existing as $e) {
                            $p = is_string($e) ? $e : ($e['path'] ?? ($e[0] ?? null));
                            if ($p) { $byPath[$p] = $e; }
                        }

                        return collect(Arr::wrap($state))
                            ->map(function ($entry) use ($existingPaths, $byPath, $uploaderId, $uploaderName, $now) {
                                if (is_string($entry)) {
                                    if (in_array($entry, $existingPaths, true)) {
                                        return $byPath[$entry] ?? $entry;
                                    }
                                    return [
                                        'path' => $entry,
                                        'name' => basename($entry),
                                        'uploaded_by' => $uploaderId,
                                        'uploaded_by_name' => $uploaderName,
                                        'uploaded_at' => $now,
                                    ];
                                }
                                return $entry;
                            })
                            ->values()->all();
                    })
                    ->visibility('public')
                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $rawState) {
                        // Normalize stored attachments to plain file paths (strings).
                        // Some existing records store attachments as arrays with metadata
                        // (path, name, uploaded_by, ...). Filament's FileUpload expects
                        // an array of strings when checking file existence, so convert here.
                        $normalized = collect(Arr::wrap($rawState))->map(function ($entry) {
                            if (is_string($entry)) {
                                return $entry;
                            }
                            if (is_array($entry) || $entry instanceof \ArrayAccess) {
                                return $entry['path'] ?? ($entry[0] ?? null);
                            }
                            return null;
                        })->filter()->values()->all();

                        $component->state($normalized);
                    })
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        // New scheme: {usernameSlug}_{originalSlug}_{date}_{rand}.{ext}
                        $user = auth()->user();
                        $userName = $user?->pharmacy_name ?? $user?->name ?? 'user';
                        $usernameSlug = Str::slug(mb_substr($userName, 0, 48), '_');

                        $originalName = $file->getClientOriginalName() ?? ($file->getFilename() ?? 'file');
                        $originalBase = pathinfo($originalName, PATHINFO_FILENAME);
                        $originalSlug = Str::slug(mb_substr($originalBase, 0, 64), '_');

                        $date = Carbon::now()->format('YmdHi');
                        $rand = Str::lower(Str::random(8));
                        $ext = $file->getClientOriginalExtension() ?: $file->extension();

                        return sprintf('%s_%s_%s_%s.%s', $usernameSlug, $originalSlug, $date, $rand, $ext);
                    })
                    ->appendFiles()
                    ->downloadable()
                    ->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll(function (): ?string {
                    $u = auth()->user();
                    if (!($u?->isClient())) {
                        return null;
                    }

                    $hasFilters =
                        ! empty(array_filter((array) request()->get('tableFilters'))) ||
                        ! empty(array_filter((array) request()->get('tableColumnSearches'))) ||
                        filled(request()->get('tableSearch')) ||
                        filled(request()->get('tableSortColumn')) ||
                        ((int) request()->get('tablePage', 1) > 1);

                    return $hasFilters ? null : '10s';
                })
            ->columns([
                Tables\Columns\TextColumn::make('user.pharmacy_name')
                    ->label(__('filament.trade.fields.pharmacy'))
                    ->formatStateUsing(fn ($state, $record) => $record->user?->pharmacy_name ?: ($record->user?->name ?? '—'))
                    ->searchable()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('lab.name')->label(__('filament.trade.fields.lab'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('product_names')->label(__('filament.trade.fields.products'))->toggleable()->wrap(),
                Tables\Columns\TextColumn::make('challenge_start')
                    ->label(__('filament.trade.fields.date_challenge'))
                    ->formatStateUsing(fn ($state, $record) =>
                        '(' . ($record->challenge_start ? $record->challenge_start->format('d-m-Y') : '—')
                        . ' au ' . ($record->challenge_end ? $record->challenge_end->format('d-m-Y') : '—') . ')'
                    )
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('compensation')->label(__('filament.trade.fields.compensation'))->badge()->formatStateUsing(fn ($record) => $record->compensation_type === 'percent' ? $record->compensation . ' %' : number_format($record->compensation ?? 0, 2, ',', ' ') . ' €'),
                Tables\Columns\TextColumn::make('sent_at')->label(__('filament.trade.fields.sent_at'))->date(),
                Tables\Columns\TextColumn::make('via')->label(__('filament.trade.fields.via')),
                Tables\Columns\IconColumn::make('received')->label(__('filament.trade.filters.received'))->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('filament.trade.fields.pharmacy'))
                    ->options(function () {
                        return User::query()
                            ->whereNotNull('pharmacy_name')
                            ->whereHas('roles', fn ($q) => $q->where('name', 'client'))
                            ->orderBy('pharmacy_name')
                            ->pluck('pharmacy_name', 'id');
                    })
                    ->searchable()
                    ->visible(function () {
                        $user = auth()->user();
                        return $user && ($user->isSuperAdmin() || $user->isAssistant());
                    })
                    ->preload()
                    ->placeholder(__('filament.purchases.filters.all'))
                    ->indicator(__('filament.purchases.filters.pharmacist')),

                Tables\Filters\SelectFilter::make('lab_id')
                    ->label(__('filament.trade.fields.lab'))
                    ->relationship('lab', 'name', function (Builder $query) {
                        $user = auth()->user();
                        if (! $user) {
                            return;
                        }

                        if ($user->isSuperAdmin()) {
                            return;
                        }

                        $labTable = $query->getModel()->getTable();

                        if ($user->isClient()) {
                            $query->whereIn("{$labTable}.id", TradeOperation::query()
                                ->select('lab_id')
                                ->where('user_id', $user->id)
                                ->whereNotNull('lab_id'));
                            return;
                        }

                       
                    })
                    ->multiple()
                    ->visible(function () {
                        $user = auth()->user();
                        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
                    }),

                // Filter by product (options limited by lab if provided)
                Tables\Filters\SelectFilter::make('product_id')
                    ->label(__('filament.trade.fields.products'))
                    ->options(function () {
                        $user = auth()->user();

                        $filters = (array) request()->input('tableFilters', []);
                        $labFilterValues = Arr::wrap(data_get($filters, 'lab_id.values', data_get($filters, 'lab_id.value', [])));
                        $labFilterValues = array_values(array_filter($labFilterValues, fn ($value) => (string) $value !== ''));
                        $labFilterIds = array_map('intval', $labFilterValues);

                        $query = Product::query()
                            ->whereHas('lab', fn ($q) => $q->whereIn('type', ['trade', 'purchase']));

                        if (! empty($labFilterIds)) {
                            $query->whereIn('lab_id', $labFilterIds);
                        }

                        if ($user?->isClient()) {
                            $query->whereIn('products.id', TradeOperation::query()
                                ->join('product_trade_operation', 'product_trade_operation.trade_operation_id', '=', 'trade_operations.id')
                                ->select('product_trade_operation.product_id')
                                ->where('trade_operations.user_id', $user->id));
                        } elseif ($user?->isAssistant()) {
                            $query->whereIn('products.id', TradeOperation::query()
                                ->join('product_trade_operation', 'product_trade_operation.trade_operation_id', '=', 'trade_operations.id')
                                ->select('product_trade_operation.product_id'));
                        }

                        return $query
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->visible(function () {
                        $user = auth()->user();
                        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
                    })
                    ->query(function (Builder $query, array $data) {
                        $values = Arr::wrap($data['values'] ?? $data['value'] ?? []);
                        $values = array_values(array_filter($values, fn ($value) => (string) $value !== ''));
                        $values = array_map('intval', $values);

                        if (! empty($values)) {
                            $query->whereHas('products', fn ($q) => $q->whereIn('products.id', $values));
                        }
                    }),

                // Filter by a date that falls within the challenge range (challenge_start..challenge_end)
                Tables\Filters\Filter::make('date_challenge')
                    ->label(__('filament.trade.fields.date_challenge'))
                    ->form([
                        Forms\Components\DatePicker::make('start')->label(__('filament.trade.placeholders.start')),
                        Forms\Components\DatePicker::make('end')->label(__('filament.trade.placeholders.end')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $hasStart = ! empty($data['start']);
                        $hasEnd = ! empty($data['end']);

                        if (! $hasStart && ! $hasEnd) {
                            return;
                        }

                        if ($hasStart && ! $hasEnd) {
                            // Match records where challenge_start equals selected start
                            $query->whereDate('challenge_start', '=', $data['start']);
                            return;
                        }

                        if ($hasEnd && ! $hasStart) {
                            // Match records where challenge_end equals selected end
                            $query->whereDate('challenge_end', '=', $data['end']);
                            return;
                        }

                        // Both provided: match exact pair
                        $query->whereDate('challenge_start', '=', $data['start'])
                              ->whereDate('challenge_end', '=', $data['end']);
                    }),
            ])

            ->actions([
                EditAction::make()
                    ->label(__('filament.actions.edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->visible(fn () => auth()->user() && (
                        auth()->user()?->isSuperAdmin() ||
                        auth()->user()?->isClient() ||
                        auth()->user()?->isAssistant()
                    )),
               
                Action::make('documents')
                    ->label(__('filament.actions.add_documents'))
                    ->icon('heroicon-m-paper-clip')
                    ->form([
                        
                        Forms\Components\FileUpload::make('attachments')
                            ->label(__('filament.actions.add_documents'))
                            ->multiple()
                            ->directory('trade/attachments')
                            ->disk('public')
                            ->visibility('public')
                            ->afterStateHydrated(function (Forms\Components\FileUpload $component, $rawState) {
                                $normalized = collect(Arr::wrap($rawState))->map(function ($entry) {
                                    if (is_string($entry)) return $entry;
                                    if (is_array($entry) || $entry instanceof \ArrayAccess) return $entry['path'] ?? ($entry[0] ?? null);
                                    return null;
                                })->filter()->values()->all();

                                $component->state($normalized);
                            })
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                $user = auth()->user();
                                $originalName = $file->getClientOriginalName() ?? ($file->getFilename() ?? 'file');
                                $originalBase = pathinfo($originalName, PATHINFO_FILENAME);
                                $originalSlug = Str::slug(mb_substr($originalBase, 0, 64), '_');
                                
                                $userName = $user?->pharmacy_name ?? $user?->name ?? 'user';
                                $usernameSlug = Str::slug(mb_substr($userName, 0, 48), '_');


                                $date = Carbon::now()->format('YmdHi');
                                $rand = Str::lower(Str::random(8));
                                $ext = $file->getClientOriginalExtension() ?: $file->extension();

                                return sprintf('%s_%s_%s_%s.%s', $usernameSlug, $originalSlug, $date, $rand, $ext);
                            })
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, TradeOperation $record) {
                        $existing = (array) ($record->attachments ?? []);
                        $new = (array) ($data['attachments'] ?? []);

                        // Keep legacy entries untouched
                        $existingNormalized = collect($existing)->map(fn ($e) => $e)->values();
                        
                        $uploaderId = auth()->id();
                        $uploaderName = optional(auth()->user())->pharmacy_name ?? optional(auth()->user())->name ?? null;
                        $now = \Illuminate\Support\Carbon::now()->toDateTimeString();

                        $newNormalized = collect($new)->map(function ($p) use ($uploaderId, $uploaderName, $now) {
                            return [
                                'path' => $p,
                                'name' => basename($p),
                                'uploaded_by' => $uploaderId,
                                'uploaded_by_name' => $uploaderName,
                                'uploaded_at' => $now,
                            ];
                        })->values();

                        $merged = $existingNormalized->concat($newNormalized)
                            ->unique(function ($item) {
                                if (is_array($item)) return $item['path'] ?? '';
                                if (is_string($item)) return $item;
                                return $item->path ?? '';
                            })
                            ->values()
                            ->all();

                        $record->update(['attachments' => $merged]);
                    })
                    ->visible(fn () => auth()->user() && (
                        auth()->user()?->isSuperAdmin() ||
                        auth()->user()?->isClient() ||
                        auth()->user()?->isAssistant()
                    )),
                Action::make('view_documents')
                    ->label(__('filament.trade.actions.view_documents'))
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->visible(fn (TradeOperation $record): bool => ! empty($record->attachments) || ! empty($record->photos))
                    ->modalHeading(__('filament.actions.documents'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament.trade.actions.close'))
                    ->modalWidth('4xl')
                    ->form([
                        Forms\Components\ViewField::make('document_viewer')
                            ->hiddenLabel()
                            ->view('filament.components.document-viewer')
                            ->viewData(fn (TradeOperation $record) => [
                                'items' => collect()
                                    ->concat((array) ($record->photos ?? []))
                                    ->concat((array) ($record->attachments ?? []))
                                    ->filter()
                                    ->map(function ($entry) use ($record) {
                                            // Normalize entry to a consistent array format
                                            if (is_string($entry)) {
                                                return [
                                                    'path' => $entry,
                                                    'name' => basename($entry),
                                                    'uploaded_by' => null,
                                                    'uploaded_by_name' => null,
                                                    'uploaded_at' => null,
                                                ];
                                            } elseif (is_array($entry) || $entry instanceof \ArrayAccess) {
                                                return [
                                                    'path' => $entry['path'] ?? ($entry[0] ?? null),
                                                    'name' => $entry['name'] ?? basename($entry['path'] ?? ''),
                                                    'uploaded_by' => $entry['uploaded_by'] ?? null,
                                                    'uploaded_by_name' => $entry['uploaded_by_name'] ?? $entry['uploaded_by'] ?? null,
                                                    'uploaded_at' => $entry['uploaded_at'] ?? null,
                                                ];
                                            }
                                            return null;
                                        })
                                    ->filter(fn ($item) => ! empty($item['path']))
                                    ->map(function ($item) {
                                        $path = $item['path'];
                                        $name = $item['name'] ?? basename($path);
                                        $uploader = $item['uploaded_by_name'] ?? null;
                                        $date = $item['uploaded_at'] ?? null;

                                        $url = Storage::disk('public')->url($path);

                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                        $isImage = in_array($ext, $imageExts, true);

                                        $formattedDate = null;
                                        if ($date) {
                                            try {
                                                $formattedDate = Carbon::parse($date)->format('Y-m-d H:i');
                                            } catch (\Throwable $e) {
                                                // ignore
                                            }
                                        }

                                        return [
                                            'path' => $path,
                                            'name' => $name,
                                            'url' => $url,
                                            'is_image' => $isImage,
                                            'uploaded_by' => $item['uploaded_by'] ?? null,
                                            'uploader' => $uploader,
                                            'date' => $formattedDate,
                                        ];
                                    })
                                    ->values(),
                                'trade' => $record,
                            ]),
                    ]),
                DeleteAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTradeOperations::route('/'),
            'create' => Pages\CreateTradeOperation::route('/create'),
            'edit' => Pages\EditTradeOperation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['lab', 'products', 'user']);
        $user = auth()->user();

        if (request()->filled('pharmacy')) {
            $pharmacyId = (int) request('pharmacy');
            $query->where('user_id', $pharmacyId);
        }

        if ($user && $user->isClient()) {
            return $query->where('user_id', $user->id);
        }

       

        return $query;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        $query = parent::getRecordRouteBindingEloquentQuery();
        $user = auth()->user();
        if ($user && $user->isClient()) {
            $query->where('user_id', $user->id);
        }
        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isClient() || $user->isAssistant());
    }
}
