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
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

use UnitEnum;

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
    protected static ?string $modelLabel = 'Achat';
    protected static ?string $pluralModelLabel = 'Achats';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('filament.purchases.fields.pharmacy'))
                    ->relationship('user', 'name', function ($query) {
                        $query->whereHas('roles', fn ($r) => $r->where('name', 'client'));
                        $u = auth()->user();
                        if ($u && method_exists($u, 'isAssistant') && $u->isAssistant()) {
                            $query->whereIn('users.id', function ($sub) use ($u) {
                                $sub->from('commercial_user as cu')
                                    ->select('cu.user_id')
                                    ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                                    ->where('c.user_id', $u->id);
                            });
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->default(fn () => request()->integer('pharmacy'))
                    ->visible(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant())
                    ->required(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isAssistant()),
                Forms\Components\Select::make('lab_id')
                    ->label(__('filament.purchases.fields.lab'))
                    ->relationship('lab', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.nav.resources.labs'))->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $lab = Lab::firstOrCreate(['name' => $data['name']], ['name' => $data['name']]);
                        return $lab->id;
                    }),
                Forms\Components\Select::make('lab_category_id')
                    ->label(__('filament.purchases.fields.category'))
                    ->options(fn (callable $get) => $get('lab_id') ? LabCategory::where('lab_id', $get('lab_id'))->pluck('name', 'id') : [])
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->visible(fn (callable $get) => (bool) $get('lab_id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.purchases.fields.category'))->required(),
                    ])
                    ->createOptionUsing(function (array $data, callable $get) {
                        $labId = $get('lab_id');
                        $cat = LabCategory::firstOrCreate(['lab_id' => $labId, 'name' => $data['name']]);
                        return $cat->id;
                    })
                    ->afterStateUpdated(fn (callable $set) => $set('lab_type_id', null)),
                Forms\Components\Select::make('lab_type_id')
                    ->label(__('filament.purchases.fields.type'))
                    ->options(fn (callable $get) => $get('lab_category_id') ? LabType::where('lab_category_id', $get('lab_category_id'))->pluck('name', 'id') : [])
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->visible(fn (callable $get) => (bool) $get('lab_category_id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.purchases.fields.type'))->required(),
                    ])
                    ->createOptionUsing(function (array $data, callable $get) {
                        $catId = $get('lab_category_id');
                        $type = LabType::firstOrCreate(['lab_category_id' => $catId, 'name' => $data['name']]);
                        return $type->id;
                    }),
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
                    ->directory('purchases/attachments')
                    ->visibility('public')
                    ->downloadable()
                    ->openable(),
                // Status temporarily hidden as requested
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('2s')
            ->columns([
                Tables\Columns\TextColumn::make('lab.name')->label(__('filament.purchases.fields.lab'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('labCategory.name')->label(__('filament.purchases.fields.category'))->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('labType.name')->label(__('filament.purchases.fields.type'))->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.pharmacist_name')
                    ->label(__('filament.purchases.fields.pharmacy'))
                    ->searchable()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                    ->formatStateUsing(fn ($state, Purchase $record) => $state ?: ($record->user?->name ?? '—')),
                Tables\Columns\TextColumn::make('commercial.name')->label(__('filament.purchases.fields.commercial_name'))->toggleable(),
                Tables\Columns\TextColumn::make('commercial.contact')->label(__('filament.purchases.fields.contact'))->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_order_date')->label(__('filament.purchases.fields.last_order_date'))->date()->sortable(),
                Tables\Columns\TextColumn::make('last_order_value')->label(__('filament.purchases.fields.last_order_value'))->money('eur', true)->sortable(),
                Tables\Columns\TextColumn::make('next_order_date')->label(__('filament.purchases.fields.next_order_date'))->date()->sortable(),
                Tables\Columns\TextColumn::make('annual_target')->label(__('filament.purchases.fields.annual_target'))->money('eur', true)->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Statut')->badge()
                    ->color(fn ($state) => match ($state) {
                        'en_attente' => 'warning',
                        'livree' => 'success',
                        'annulee' => 'danger',
                        default => null,
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'en_attente' => 'En attente',
                        'livree' => 'Livrée',
                        'annulee' => 'Annulée',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('filament.purchases.fields.pharmacy'))
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                    ->options(function () {
                        return User::query()
                            ->whereNotNull('pharmacist_name')
                            ->whereHas('roles', fn ($q) => $q->where('name', User::ROLE_CLIENT))
                            ->orderBy('pharmacist_name')
                            ->pluck('pharmacist_name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Tous')
                    ->indicator('Pharmacien'),
                Tables\Filters\SelectFilter::make('lab_id')->relationship('lab', 'name')->label(__('filament.purchases.fields.lab'))
                ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
,
                Tables\Filters\SelectFilter::make('status')->options([
                    'en_attente' => 'En attente',
                    'livree' => 'Livrée',
                    'annulee' => 'Annulée',
                ]),
                Filter::make('recent')
                    ->label('Commandes récentes')
                    ->query(fn (Builder $q) => $q->where('last_order_date', '>=', now()->subMonth())),
            ])
            ->actions([
                EditAction::make()
                    ->label('Modifier')
                    ->icon('heroicon-m-pencil-square')
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                Action::make('documents')
                    ->label('Documents')
                    ->icon('heroicon-m-paper-clip')
                    ->form([
                        Forms\Components\Placeholder::make('existing')
                            ->label('Fichiers existants')
                            ->content(function (Purchase $record) {
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
                    ->action(function (array $data, Purchase $record) {
                        $existing = (array) ($record->attachments ?? []);
                        $new = (array) ($data['attachments'] ?? []);
                        $record->update(['attachments' => array_values(array_unique(array_merge($existing, $new)))]);
                    })
                    ->visible(fn () => auth()->user()?->isClient() ?? false),
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
