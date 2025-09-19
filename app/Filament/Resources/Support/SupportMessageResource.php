<?php

namespace App\Filament\Resources\Support;

use App\Filament\Resources\Support\Pages\ListSupportMessages;
use App\Mail\ContactReplyMail;
use App\Models\Contact;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class SupportMessageResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = 'Messages de support';

    protected static ?string $modelLabel = 'Message de support';

    protected static UnitEnum|string|null $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.support_clients');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.groups.support');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Téléphone')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('message')->label('Message')->wrap()->lineClamp(2)->searchable(),
                TextColumn::make('created_at')->label('Reçu le')->dateTime()->sortable(),
                TextColumn::make('replied_at')
                    ->label('Répondu')
                    ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->sortable(),
            ])
            ->filters([
                // no extra filters for now
            ])
            ->actions([
                Action::make('voir')
                    ->label('Voir')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Message')
                    ->modalSubmitAction(false)
                    ->disabledForm()
                    ->form([
                        Tables\Columns\TextColumn::make('name')->label('Nom'),
                    ])
                    ->hidden(), // use built-in row preview pattern via custom action below if needed

                Action::make('details')
                    ->label('Détails')
                    ->color('gray')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Détails du message')
                    ->modalSubmitAction(false)
                    ->form([
                        \Filament\Forms\Components\Placeholder::make('nom')->label('Nom')->content(fn ($record) => $record->name),
                        \Filament\Forms\Components\Placeholder::make('email')->label('Email')->content(fn ($record) => $record->email),
                        \Filament\Forms\Components\Placeholder::make('phone')->label('Téléphone')->content(fn ($record) => $record->phone ?: '—'),
                        \Filament\Forms\Components\Textarea::make('message')->label('Message')->rows(8)
                            ->disabled()
                            ->default(fn ($record) => $record->message),
                        \Filament\Forms\Components\Placeholder::make('replied_at')->label('Répondu le')->content(fn ($record) => optional($record->replied_at)->format('d/m/Y H:i') ?: 'Non'),
                        \Filament\Forms\Components\Textarea::make('reply_message')->label('Dernière réponse envoyée')
                            ->disabled()
                            ->default(fn ($record) => $record->reply_message),
                    ]),

                Action::make('repondre')
                    ->label('Envoyer l’email')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('primary')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->label('Objet')
                            ->default('Réponse à votre demande de support')
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('body')
                            ->label('Message de réponse')
                            ->rows(8)
                            ->required(),
                    ])
                    ->action(function (array $data, Contact $record): void {
                        // Send reply email
                        \Illuminate\Support\Facades\Mail::to($record->email)
                            ->queue(new ContactReplyMail($record, $data['subject'], $data['body']));

                        // Update record meta
                        $record->update([
                            'replied_at' => now(),
                            'reply_message' => $data['body'],
                            'replied_by' => optional(auth()->user())->id,
                        ]);
                    })
                    ->after(fn () => \Filament\Notifications\Notification::make()
                        ->title('Réponse envoyée')
                        ->success()
                        ->send()
                    ),
            ])
            ->bulkActions([
                // none
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportMessages::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_type', 'client');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('super_admin') || $user->hasRole('assistant'));
    }
}
