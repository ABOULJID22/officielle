<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Resources\Purchases\Pages\EditPurchase;
use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Models\Commercial;
use App\Models\Lab;
use App\Models\LabCategory;
use App\Models\LabType;
use App\Models\Purchase;
use App\Models\User;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Grid;
class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    //protected static UnitEnum|string|null $navigationGroup = null;

    protected static ?string $navigationLabel = null;
    
    // Lower values appear first in the sidebar
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.purchases');
    }

    /* public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.trade');
    } */
    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('filament.purchases.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.purchases.models');
    }

    public static function form(Schema $schema): Schema
    {
        // Ensure public directories exist so FileUpload can store files there
        try {
            Storage::disk('public')->makeDirectory('purchases/attachments');
        } catch (\Throwable $e) {
            // ignore - upload will fail later if disk misconfigured
        }

        return $schema
            ->columns(2)
            ->schema([
                    Forms\Components\Select::make('user_id')
                            ->label(__('filament.purchases.fields.pharmacy'))
                            ->relationship('user', 'name', function ($query) {
                                // On limite uniquement aux utilisateurs avec le rôle "client"
                                $query->whereHas('roles', fn ($r) => $r->where('name', 'client'));
                            })
                         ->getOptionLabelFromRecordUsing(fn ($record) => $record->pharmacy_name ?: $record->name) // Affiche pharmacy_name si dispo, sinon name
                            ->searchable()
                            ->preload()
                            ->default(fn () => request()->integer('pharmacy'))
                            ->visible(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant())
                            ->required(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant()),
                    Forms\Components\Select::make('lab_id')
                            ->label(__('filament.purchases.fields.lab'))
                            // Allow selecting any existing lab that is either type 'purchase' or 'trade'
                            ->relationship('lab', 'name', function ($query) {
                                $query->whereIn('type', ['purchase', 'trade']);
                            })
                            ->placeholder(__('filament.purchases.placeholders.lab'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\Select::make('existing_category_id')
                                    ->label(__('filament.purchases.fields.category'))
                                    ->options(fn (callable $get) =>
                                        LabCategory::where(function($q){ $q->where('category_type','purchase')->orWhereNull('category_type'); })
                                            ->get()
                                            ->unique('name')
                                            ->pluck('name','id')
                                    )
                                    ->placeholder(__('filament.purchases.placeholders.choose_existing_category'))
                                    ->reactive()
                                    ,
                                Forms\Components\TextInput::make('name')
                                        ->label(__('filament.nav.resources.labs'))
                                        ->required(),
                                Forms\Components\Repeater::make('categories')
                                        ->label(__('filament.purchases.fields.category'))
                                        ->minItems(0)
                                        ->collapsible()
                                        ->reorderable()
                                        ->hidden(fn (callable $get) => (bool) $get('existing_category_id'))
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label(__('filament.purchases.fields.category'))
                                                ->required(),
                                            ]),
                            ])
                            ->createOptionUsing(function (array $data) {
                                // Create or find the lab
                                $lab = Lab::firstOrCreate(['name' => $data['name']], ['name' => $data['name'], 'type' => 'purchase']);

                                // If user selected an existing global category, duplicate it for this lab
                                $existingCategoryId = $data['existing_category_id'] ?? null;
                                if ($existingCategoryId) {
                                    $existing = LabCategory::find($existingCategoryId);
                                    if ($existing) {
                                        LabCategory::firstOrCreate([
                                            'lab_id' => $lab->id,
                                            'name' => $existing->name,
                                        ], [
                                            'lab_id' => $lab->id,
                                            'name' => $existing->name,
                                            'category_type' => 'purchase',
                                        ]);
                                    }
                                }

                                // Also create any new categories entered in the repeater
                                $categories = (array)($data['categories'] ?? []);
                                if ($lab && !empty($categories)) {
                                    foreach ($categories as $cat) {
                                        if (!empty($cat['name'])) {
                                            LabCategory::firstOrCreate([
                                                'lab_id' => $lab->id,
                                                'name' => $cat['name'],
                                            ], [
                                                'lab_id' => $lab->id,
                                                'name' => $cat['name'],
                                                'category_type' => 'purchase',
                                            ]);
                                        }
                                    }
                                }

                                return $lab->id;
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('lab_category_id', null);
                                $set('lab_type_id', null);
                            }),
                Forms\Components\Select::make('lab_category_id')
                    ->label(__('filament.purchases.fields.category'))
                    ->options(fn (callable $get) => $get('lab_id') ? LabCategory::where('lab_id', $get('lab_id'))
                        ->where(function($q){ $q->where('category_type','purchase')->orWhereNull('category_type'); })
                        ->get()
                        ->unique('name')
                        ->pluck('name', 'id') : [])
                    ->placeholder(__('filament.purchases.placeholders.category'))
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required(fn (callable $get) => (bool) $get('lab_id'))
                    ->visible(fn (callable $get) => (bool) $get('lab_id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.purchases.fields.category'))->required(),
                    ])
                    ->createOptionUsing(function (array $data, callable $get) {
                        $labId = $get('lab_id');
                        $cat = LabCategory::firstOrCreate(['lab_id' => $labId, 'name' => $data['name']], ['lab_id' => $labId, 'name' => $data['name'], 'category_type' => 'purchase']);
                        return $cat->id;
                    })
                    ->afterStateUpdated(fn (callable $set) => $set('lab_type_id', null)),
                Forms\Components\Select::make('commercial_id')
                    ->label(__('filament.purchases.fields.commercial_name'))
                    ->relationship('commercial', 'name')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.purchases.fields.commercial_name'))->required(),
                        Forms\Components\TextInput::make('contact')->label(__('filament.purchases.fields.contact'))->maxLength(191),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $commercial = \App\Models\Commercial::create([
                            'name' => $data['name'],
                            'contact' => $data['contact'] ?? null,
                        ]);
                        return $commercial->id;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $commercial = Commercial::find($state);
                        $set('commercial_contact', $commercial?->contact);
                    }),
                Forms\Components\TextInput::make('commercial_contact')
                    ->label(__('filament.purchases.fields.contact'))
                    ->maxLength(191)
                    ->dehydrated(false)
                    ->readOnly()
                    ->afterStateHydrated(function (callable $set, ?Purchase $record) {
                        if ($record) {
                            $set('commercial_contact', optional($record->commercial)->contact);
                        }
                    })
                    ->helperText(__('filament.purchases.fields.contact')), 
                Forms\Components\DatePicker::make('last_order_date')
                    ->label(__('filament.purchases.fields.last_order_date')),
                Forms\Components\TextInput::make('last_order_value')
                    ->label(__('filament.purchases.fields.last_order_value'))
                    ->numeric()
                    ->rule('numeric')
                    ->minValue(0)
                    ->step('0.01'),
                Forms\Components\DatePicker::make('next_order_date')
                    ->label(__('filament.purchases.fields.next_order_date')),
                Forms\Components\TextInput::make('annual_target')
                    ->label(__('filament.purchases.fields.annual_target'))
                    ->numeric()
                    ->rule('numeric')
                    ->minValue(0)
                    ->step('0.01'),
                Forms\Components\FileUpload::make('attachments')
                    ->label(__('filament.purchases.fields.attachments'))
                    ->multiple()
                    ->disk('public')
                    ->directory('purchases/attachments')
                    ->dehydrateStateUsing(function ($state, callable $get, ?Purchase $record) {
                        // Add owner metadata only for files newly added in this form submit.
                        // Keep legacy string entries that already existed as strings (no artificial uploader).
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
                            ->filter()
                            ->values()
                            ->all();
                        $existingByPath = [];
                        foreach ($existing as $e) {
                            $p = is_string($e) ? $e : ($e['path'] ?? ($e[0] ?? null));
                            if ($p) { $existingByPath[$p] = $e; }
                        }

                        return collect(Arr::wrap($state))
                            ->map(function ($entry) use ($existingPaths, $existingByPath, $uploaderId, $uploaderName, $now) {
                                if (is_string($entry)) {
                                    // If already existed in record, restore the original entry (to keep metadata if any)
                                    if (in_array($entry, $existingPaths, true)) {
                                        $orig = $existingByPath[$entry] ?? null;
                                        return $orig ?? $entry; // keep as string if original was string
                                    }
                                    // New upload in this submit: add owner metadata
                                    if (! in_array($entry, $existingPaths, true)) {
                                        return [
                                            'path' => $entry,
                                            'name' => basename($entry),
                                            'uploaded_by' => $uploaderId,
                                            'uploaded_by_name' => $uploaderName,
                                            'uploaded_at' => $now,
                                        ];
                                    }
                                    return $entry; // fallback
                                }
                                // Already structured entry remains unchanged
                                return $entry;
                            })
                            ->values()
                            ->all();
                    })
                    ->visibility('public')
                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $rawState) {
                        $normalized = collect(Arr::wrap($rawState))->map(function ($entry) {
                            if (is_string($entry)) return $entry;
                            if (is_array($entry) || $entry instanceof \ArrayAccess) return $entry['path'] ?? ($entry[0] ?? null);
                            return null;
                        })->filter()->values()->all();

                        $component->state($normalized);
                    })
                    ->getUploadedFileNameForStorageUsing(function ($file): string {
                        // New scheme: {usernameSlug}_{originalSlug}_{date}_{rand}.{ext} (no IDs/roles)
                        $user = auth()->user();
                        $usernameSlug = $user ? Str::slug(mb_substr($user->pharmacy_name ?? $user->name ?? 'user', 0, 48), '_') : 'user';

                        $originalName = method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : ($file->getFilename() ?? 'file');
                        $originalBase = pathinfo($originalName, PATHINFO_FILENAME);
                        $originalSlug = Str::slug(mb_substr($originalBase, 0, 64), '_');

                        $date = Carbon::now()->format('YmdHi');
                        $rand = Str::lower(Str::random(8));
                        $ext = method_exists($file, 'getClientOriginalExtension') && $file->getClientOriginalExtension()
                            ? $file->getClientOriginalExtension()
                            : (method_exists($file, 'extension') ? $file->extension() : 'dat');

                        return sprintf('%s_%s_%s_%s.%s', $usernameSlug, $originalSlug, $date, $rand, $ext);
                    })
                    ->appendFiles()
                    ->downloadable()
                    ->openable(),
                // Status temporarily hidden as requested
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lab.name')
                    ->label(__('filament.purchases.fields.lab'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lab_category_name')
                    ->label(__('filament.purchases.fields.category'))
                    ->getStateUsing(fn (Purchase $record) => $record->labCategory?->name ?? '—'),
                Tables\Columns\TextColumn::make('user.pharmacy_name')
                    ->label(__('filament.purchases.fields.pharmacy'))
                    ->searchable(query: function ($query, $search) {
                        $query
                            ->where('pharmacy_name', 'like', "%{$search}%")
                            ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                    ->formatStateUsing(fn ($state, Purchase $record) => 
                        $state ?: ($record->user?->name ?? '—')
                    ),
                 /*Tables\Columns\TextColumn::make('user.pharmacist_name')->label('Responsable')->searchable(),*/                
                Tables\Columns\TextColumn::make('commercial.name')->label(__('filament.purchases.fields.commercial_name'))->toggleable(),
                Tables\Columns\TextColumn::make('commercial.contact')->label(__('filament.purchases.fields.contact')),
                Tables\Columns\TextColumn::make('last_order_date')
                    ->label(__('filament.purchases.fields.last_order_date'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::parse($state)
                        ->locale(app()->getLocale())
                        ->isoFormat('MMM D, YYYY') : '—'),
                Tables\Columns\TextColumn::make('last_order_value')->label(__('filament.purchases.fields.last_order_value'))->money('eur', true)->sortable(),
                Tables\Columns\TextColumn::make('next_order_date')
                    ->label(__('filament.purchases.fields.next_order_date'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::parse($state)
                        ->locale(app()->getLocale())
                        ->isoFormat('MMM D, YYYY') : '—'),
                Tables\Columns\TextColumn::make('annual_target')->label(__('filament.purchases.fields.annual_target'))->money('eur', true)->sortable(),
                /* Tables\Columns\TextColumn::make('status')->label(__('filament.purchases.fields.status'))->badge()
                    ->color(fn ($state) => match ($state) {
                        'en_attente' => 'warning',
                        'livree' => 'success',
                        'annulee' => 'danger',
                        default => null,
                    })
                    ->formatStateUsing(function ($state) {
                        $key = 'filament.purchases.statuses.' . $state;
                        $translated = __($key);
                        return $translated !== $key ? $translated : ($state ?? '—');
                    }), */
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                        ->label(__('filament.purchases.fields.pharmacy'))
                        ->visible(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant())
                        ->relationship('user', 'name', function ($query) {
                            $query->whereHas('roles', fn ($r) => $r->where('name', 'client'));
                        })
                        ->options(fn () =>
                            User::query()
                                ->whereHas('roles', fn ($r) => $r->where('name', 'client'))
                                ->orderBy('pharmacy_name')
                                ->get()
                                ->mapWithKeys(fn ($u) => [$u->id => $u->pharmacy_name ?: $u->name])
                        )
                        ->searchable()
                        ->preload()
                        ->placeholder(__('filament.purchases.filters.all'))
                        ->indicator(__('filament.purchases.filters.pharmacist')),

                Tables\Filters\SelectFilter::make('lab_id')
                    ->label(__('filament.purchases.fields.lab'))
                    ->relationship('lab', 'name', function (Builder $query) {
                        $user = auth()->user();
                        if (! $user) {
                            return;
                        }

                        $labTable = $query->getModel()->getTable();

                        if ($user->isClient()) {
                            $query->whereIn("{$labTable}.id", Purchase::query()
                                ->select('lab_id')
                                ->where('user_id', $user->id)
                                ->whereNotNull('lab_id'));
                            return;
                        }

                        if ($user->isAssistant()) {
                            $query->whereIn("{$labTable}.id", Purchase::query()
                                ->select('lab_id')
                                ->whereNotNull('lab_id')
                                ->whereIn('user_id', function ($sub) use ($user) {
                                    $sub->from('commercial_user as cu')
                                        ->select('cu.user_id')
                                        ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                                        ->where('c.user_id', $user->id);
                                }));
                        }
                    })
                    ->multiple()
                    ->visible(function () {
                        $user = auth()->user();
                        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
                    }),

                Tables\Filters\SelectFilter::make('lab_category_name')
                    ->label(__('filament.purchases.fields.category'))
                    ->options(function () {
                        $user = auth()->user();

                        $filters = (array) request()->input('tableFilters', []);
                        $labFilterValues = Arr::wrap(data_get($filters, 'lab_id.values', []));
                        $labFilterValues = array_values(array_filter($labFilterValues, fn ($value) => (string) $value !== ''));
                        $labFilterIds = array_map('intval', $labFilterValues);

                        $query = LabCategory::query()
                            ->where(function ($q) {
                                $q->where('category_type', 'purchase')->orWhereNull('category_type');
                            })
                            ->whereHas('lab', fn ($q) => $q->whereIn('type', ['purchase', 'trade']));

                        if (! empty($labFilterIds)) {
                            $query->whereIn('lab_id', $labFilterIds);
                        }

                        if ($user?->isClient()) {
                            $query->whereIn('id', Purchase::query()
                                ->select('lab_category_id')
                                ->where('user_id', $user->id)
                                ->whereNotNull('lab_category_id'));
                        } elseif ($user?->isAssistant()) {
                            $query->whereIn('id', Purchase::query()
                                ->select('lab_category_id')
                                ->whereNotNull('lab_category_id')
                                ->whereIn('user_id', function ($sub) use ($user) {
                                    $sub->from('commercial_user as cu')
                                        ->select('cu.user_id')
                                        ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                                        ->where('c.user_id', $user->id);
                                }));
                        }

                        return $query
                            ->orderBy('name')
                            ->get()
                            ->unique('name')
                            ->mapWithKeys(fn (LabCategory $category) => [$category->name => $category->name])
                            ->all();
                    })
                    ->placeholder(__('filament.purchases.filters.all'))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->visible(function () {
                        $user = auth()->user();
                        return $user && ($user->isSuperAdmin() || $user->isAssistant() || $user->isClient());
                    })
                    ->query(function (Builder $query, array $data) {
                        $values = Arr::wrap($data['values'] ?? $data['value'] ?? []);
                        $values = array_filter($values, fn ($value) => (string) $value !== '');

                        if (! empty($values)) {
                            $query->whereHas('labCategory', fn ($relation) => $relation->whereIn('name', $values));
                        }
                    }),

                Tables\Filters\Filter::make('last_order_date')
                    ->label(__('filament.purchases.fields.last_order_date'))
                    ->form([
                        Forms\Components\DatePicker::make('date')->label(__('filament.purchases.fields.last_order_date')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['date'])) {
                            $query->whereDate('last_order_date', '=', $data['date']);
                        }
                    }),

                Tables\Filters\Filter::make('next_order_date')
                    ->label(__('filament.purchases.fields.next_order_date'))
                    ->form([
                        Forms\Components\DatePicker::make('date')->label(__('filament.purchases.fields.next_order_date')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['date'])) {
                            $query->whereDate('next_order_date', '=', $data['date']);
                        }
                    }),


            ])
            ->actions([
                EditAction::make()
                    ->label(__('filament.actions.edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                Action::make('documents')
                    ->label(__('filament.actions.add_documents'))
                    ->icon('heroicon-m-paper-clip')
                    ->form([
                        Forms\Components\FileUpload::make('attachments')
                            ->label(__('filament.actions.add_documents'))
                            ->multiple()
                            ->directory('purchases/attachments')
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
                    ->action(function (array $data, Purchase $record) {
                        $existing = (array) ($record->attachments ?? []);
                        $new = (array) ($data['attachments'] ?? []);

                        // Keep legacy entries untouched (strings remain strings)
                        $existingNormalized = collect($existing)->map(fn ($e) => $e)->values();

                        $uploaderId = auth()->id();
                        $uploaderName = optional(auth()->user())->pharmacy_name ?? optional(auth()->user())->name ?? null;
                        $now = \Illuminate\Support\Carbon::now()->toDateTimeString();

                        // Store owner metadata for backend authorization; UI fields are derived later
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
                                if (is_string($item)) return $item; // path string
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
                    ->label(__('filament.actions.view_documents'))
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->visible(fn (Purchase $record): bool => ! empty($record->attachments) || ! empty($record->photos ?? []))
                        ->modalHeading(__('filament.actions.attached_documents'))
                        ->modalSubmitAction(false)
                        /* ->modalCancelActionLabel('Fermer') */
                        ->modalWidth('4xl')
                        ->form([
                            Forms\Components\ViewField::make('document_viewer')
                                ->hiddenLabel()
                                ->view('filament.components.document-viewer')
                                ->viewData(fn (Purchase $record) => [
                                    'items' => collect()
                                        ->concat((array) ($record->photos ?? []))
                                        ->concat((array) ($record->attachments ?? []))
                                        ->filter()
                                        ->map(function ($entry) use ($record) {
                                            if (is_string($entry)) {
                                                return [
                                                    'path' => $entry,
                                                    'name' => basename($entry),
                                                    // No uploader metadata for legacy string entries
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
                                        'purchase' => $record,
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Deep-link filter from Pharmacy relation via ?pharmacy=<id>
        if (request()->filled('pharmacy')) {
            $pharmacyId = (int) request('pharmacy');
            $query->where('user_id', $pharmacyId);
        }

        // Support server-side sorting by lab.name
        try {
            $sortColumn = request()->get('tableSortColumn');
            $sortDirection = request()->get('tableSortDirection') ?? 'asc';
            if ($sortColumn === 'lab.name') {
                $query->leftJoin('labs', 'labs.id', '=', 'purchases.lab_id')
                      ->orderBy('labs.name', $sortDirection)
                      ->select('purchases.*');
            }
        } catch (\Throwable $e) {
            // ignore — if request not present or not interactive, do nothing
        }

        if ($user && $user->isClient()) {
            return $query->where('user_id', $user->id);
        }

        if ($user && $user->isAssistant()) {
            // Assistants see purchases for their assigned client pharmacies via commercials mapping
            return $query->whereIn('user_id', function ($sub) use ($user) {
                $sub->from('commercial_user as cu')
                    ->select('cu.user_id')
                    ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                    ->where('c.user_id', $user->id);
            });
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
