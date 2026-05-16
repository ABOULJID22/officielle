<?php

namespace App\Filament\Resources\PharmacistRequests;

use App\Filament\Resources\PharmacistRequests\Pages\ListPharmacistRequests;
use App\Models\PharmacistRequest;
use App\Models\User;
use App\Notifications\PharmacistRequestApproved;
use App\Notifications\PharmacistRequestRejected;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
class PharmacistRequestResource extends Resource
{
    protected static ?string $model = PharmacistRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = null;
    protected static ?string $modelLabel = 'Demande de participation';
    protected static ?string $pluralModelLabel = 'Demandes de participation';
    protected static UnitEnum|string|null $navigationGroup = null;
    protected static ?int $navigationSort = 60;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav.resources.pharmacist_requests');
    }

  

    public static function canAccess(): bool
    {
        $u = auth()->user();
        return $u && $u->isSuperAdmin();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PharmacistRequest::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Utilisateur')->searchable(),
                Tables\Columns\TextColumn::make('applicant_name')->label('Demandeur')->searchable(),
                Tables\Columns\TextColumn::make('applicant_email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('pharmacy_name')->label('Pharmacie')->searchable(),
                Tables\Columns\TextColumn::make('message')->label('Message')->limit(60),
                Tables\Columns\TextColumn::make('status')->label('Statut')->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => null,
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Soumise le')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'En attente',
                    'approved' => 'Approuvée',
                    'rejected' => 'Rejetée',
                ])->default('pending'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approuver')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (PharmacistRequest $record) => $record->status === PharmacistRequest::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function (PharmacistRequest $record) {
                        $record->update([
                            'status' => PharmacistRequest::STATUS_APPROVED,
                            'approved_at' => now(),
                            'admin_note' => $record->admin_note,
                        ]);

                        $user = $record->user;
                        if ($user && ! $user->hasRole(User::ROLE_CLIENT)) {
                            $user->assignRole(User::ROLE_CLIENT);
                        }
                        if ($user) {
                            $user->notify(new PharmacistRequestApproved($record));
                        }

                        // Send a personal approval email to the requester
                        try {
                            $to = $record->applicant_email ?? $user?->email;
                            if (! empty($to)) {
                                \Illuminate\Support\Facades\Mail::to($to)
                                    ->send(new \App\Mail\PharmacistRequestApprovedMail($record));
                            }
                        } catch (\Throwable $e) {
                            // ignore mail errors
                        }
                    }),
                Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(fn (PharmacistRequest $record) => $record->status === PharmacistRequest::STATUS_PENDING)
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Motif du rejet')
                            ->required(),
                    ])
                    ->action(function (PharmacistRequest $record, array $data) {
                        $record->update([
                            'status' => PharmacistRequest::STATUS_REJECTED,
                            'rejected_at' => now(),
                            'admin_note' => $data['reason'] ?? null,
                        ]);
                        if ($record->user) {
                            $record->user->notify(new PharmacistRequestRejected($record));
                        }
                    }),
            DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->button()
                    ->color('danger'),
            ])
            ->bulkActions([
                 BulkActionGroup::make([
                  DeleteBulkAction::make()->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                ]),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPharmacistRequests::route('/'),
        ];
    }
}
