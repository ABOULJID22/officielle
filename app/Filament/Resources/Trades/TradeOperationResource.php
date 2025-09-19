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
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;

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
    protected static ?string $modelLabel = 'Opération Trade';
    protected static ?string $pluralModelLabel = 'Opérations Trade';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('filament.trade.fields.pharmacy'))
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
                    ->label(__('filament.trade.fields.lab'))
                    ->relationship('lab', 'name')
                    ->searchable()->preload()->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->label(__('filament.nav.resources.labs'))->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $lab = \App\Models\Lab::firstOrCreate(['name' => $data['name']], ['name' => $data['name']]);
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
                    ->placeholder('Début'),
                Forms\Components\DatePicker::make('challenge_end')
                    ->hiddenLabel()
                    ->placeholder('Fin'),
                Forms\Components\Hidden::make('product_id'),
                Forms\Components\TextInput::make('compensation')->label(__('filament.trade.fields.compensation'))->numeric()->step('0.01')->minValue(0),
                Forms\Components\Select::make('compensation_type')->label(__('filament.trade.fields.compensation_type'))->options([
                    'amount' => 'Montant',
                    'percent' => 'Pourcentage',
                ])->default('amount')->native(false),
                Forms\Components\DatePicker::make('sent_at')->label(__('filament.trade.fields.sent_at')),
                Forms\Components\TextInput::make('via')->label(__('filament.trade.fields.via'))->maxLength(100),
                Forms\Components\FileUpload::make('contract_path')
                    ->label(__('filament.trade.fields.contract_file'))
                    ->directory('trade/contracts')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->visibility('public'),
                Forms\Components\Toggle::make('received')->label(__('filament.trade.fields.received')),
                Forms\Components\FileUpload::make('photos')
                    ->label(__('filament.trade.fields.photos'))
                    ->multiple()
                    ->image()
                    ->directory('trade/photos')
                    ->reorderable()
                    ->visibility('public')
                    ->downloadable()
                    ->appendFiles(),
                Forms\Components\FileUpload::make('attachments')
                    ->label(__('filament.trade.fields.attachments'))
                    ->multiple()
                    ->directory('trade/attachments')
                    ->visibility('public')
                    ->downloadable()
                    ->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('2s')
            ->columns([
                Tables\Columns\TextColumn::make('user.pharmacist_name')
                    ->label(__('filament.trade.fields.pharmacy'))
                    ->formatStateUsing(fn ($state, $record) => $record->user?->pharmacist_name ?: ($record->user?->name ?? '—'))
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
                            ->whereNotNull('pharmacist_name')
                            ->whereHas('roles', fn ($q) => $q->where('name', 'client'))
                            ->orderBy('pharmacist_name')
                            ->pluck('pharmacist_name', 'id');
                    })
                    ->searchable()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
                    ->preload()
                    ->placeholder('Tous')
                    ->indicator('Pharmacien'),
                
                Tables\Filters\SelectFilter::make('lab_id')->relationship('lab', 'name')->label(__('filament.trade.fields.lab'))
                                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
,
                Tables\Filters\SelectFilter::make('product')
                    ->label(__('filament.trade.fields.product'))
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
                    })
                  ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false)
,
            ])
            ->actions([
                EditAction::make()
                    ->label('Modifier')
                    ->icon('heroicon-m-pencil-square')
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                Action::make('upload_photos')
                    ->label('Envoyer photos')
                    ->icon('heroicon-m-photo')
                    ->form([
                        Forms\Components\FileUpload::make('photos')
                            ->label('Ajouter des photos / justificatifs')
                            ->multiple()
                            ->image()
                            ->directory('trade/photos')
                            ->reorderable()
                            ->visibility('public')
                            ->downloadable()
                            ->appendFiles(),
                    ])
                    ->action(function (array $data, TradeOperation $record) {
                        $existing = (array) ($record->photos ?? []);
                        $new = (array) ($data['photos'] ?? []);
                        $record->update(['photos' => array_values(array_unique(array_merge($existing, $new)))]);
                    })
                    ->visible(fn () => auth()->user()?->isClient() ?? false),
                Action::make('documents')
                    ->label('Documents')
                    ->icon('heroicon-m-paper-clip')
                    ->form([
                        Forms\Components\Placeholder::make('existing')
                            ->label('Fichiers existants')
                            ->content(function (TradeOperation $record) {
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
                            ->directory('trade/attachments')
                            ->visibility('public')
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, TradeOperation $record) {
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

        if ($user && $user->isAssistant()) {
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
