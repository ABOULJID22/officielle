<?php

namespace App\Filament\Resources\Calendar;

use App\Filament\Resources\Calendar\Pages\CreateEvent;
use App\Filament\Resources\Calendar\Pages\EditEvent;
use App\Filament\Resources\Calendar\Pages\ListEvents;
use App\Filament\Resources\Calendar\RelationManagers\NotesRelationManager;
use App\Models\Event;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 90;

    protected static UnitEnum|string|null $navigationGroup = 'Paramètres';


    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(191),
            Forms\Components\Textarea::make('description')->label('Description')->rows(3),
            Forms\Components\DateTimePicker::make('start_at')->label('Début')->required()->native(false),
            Forms\Components\DateTimePicker::make('end_at')->label('Fin')->native(false),
            Forms\Components\Select::make('user_id')->label('Calendrier (pharmacie)')
                ->relationship('user', 'name')
                ->searchable()->preload()->helperText('Laisser vide pour événement global')
                ->visible(fn() => auth()->user()?->isSuperAdmin() ?? false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('all_day')->label('Jour entier')->boolean(),
                Tables\Columns\TextColumn::make('start_at')->label('Début')->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('end_at')->label('Fin')->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('user.name')->label('Calendrier'),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Du'),
                        Forms\Components\DatePicker::make('until')->label('Au'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['from'])) {
                            $from = \Carbon\Carbon::parse($data['from'])->startOfDay();
                            $query->where('start_at', '>=', $from);
                        }
                        if (!empty($data['until'])) {
                            $until = \Carbon\Carbon::parse($data['until'])->endOfDay();
                            $query->where('start_at', '<=', $until);
                        }
                    }),

                \Filament\Tables\Filters\SelectFilter::make('user_id')
                    ->label('Calendrier (pharmacie)')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
        ];
    }



    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $u = auth()->user();

        if (! $u) {
            return $query->whereRaw('1=0');
        }

        // Prefer explicit isSuperAdmin() if present
        if (method_exists($u, 'isSuperAdmin') && $u->isSuperAdmin()) {
            return $query;
        }

        // Support role-based checks (e.g., Spatie) as fallback
        if (method_exists($u, 'hasRole')) {
            try {
                if ($u->hasRole('super-admin') || $u->hasRole('superadmin') || $u->hasRole('super_admin')) {
                    return $query;
                }
            } catch (\Throwable $e) {
                // ignore and fall through to restrictive query
            }
        }

        // Non super-admins: show only their own events
        return $query->where('user_id', $u->id);
    }

public static function canAccess(): bool
    {
        $u = auth()->user();
        return $u && $u->isSuperAdmin();
    }
    
}
