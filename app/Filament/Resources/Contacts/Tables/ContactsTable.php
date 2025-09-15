<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use App\Exports\ContactsExport;
use App\Models\Contact;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('user_type')
                    ->label('Vous êtes')
                    ->formatStateUsing(fn (string $state, $record) =>
                        $record->user_other ? "{$state} — {$record->user_other}" : $state
                    )
                    ->wrap()
                    ->lineClamp(2)
                    ->searchable(),
                // Retirer la colonne user_other devenue inutile

                TextColumn::make('message')
                    ->label('Message')
                    ->wrap()
                    ->lineClamp(2)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Profession')
                    ->options([
                        'Acheteur' => 'Acheteur',
                        'Futur pharmacien' => 'Futur pharmacien',
                        'Pharmacien titulaire' => 'Pharmacien titulaire',
                        'Autres' => 'Autres',
                    ])
                    ->multiple(),

                TernaryFilter::make('professionnel')
                    ->label('Professionnel')
                    ->queries(
                        true: fn (Builder $query) => $query->whereIn('user_type', [
                            'Futur pharmacien',
                            'Pharmacien titulaire',
                        ]),
                        false: fn (Builder $query) => $query->whereNotIn('user_type', [
                            'Futur pharmacien',
                            'Pharmacien titulaire',
                        ]),
                        blank: fn (Builder $query) => $query,
                    ),

                // Filtre par période de création
                Filter::make('created_between')
                    ->label('Date de création')
                    ->form([
                        DatePicker::make('from')
                            ->label('Du')
                            ->displayFormat('d-m-Y')
                            ->native(false),
                        DatePicker::make('until')
                            ->label('Au')
                            ->displayFormat('d-m-Y')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Action::make('voir')
                    ->label('Voir')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Message de contact')
                    ->modalSubmitAction(false)
                    ->disabledForm()
                    ->form([
                        TextInput::make('name')->label('Nom'),
                        TextInput::make('email')->label('Email'),
                        TextInput::make('phone')->label('Téléphone'),
                        // Un seul champ combiné: user_type (+ user_other si présent)
                        Placeholder::make('vous_etes')
                            ->label('Vous êtes')
                            ->content(fn ($record) =>
                                $record->user_other
                                    ? "{$record->user_type} — {$record->user_other}"
                                    : $record->user_type
                            ),
                        Textarea::make('message')->label('Message')->rows(10),
                    ])
                    ->fillForm(fn ($record) => [
                        'name' => $record->name,
                        'email' => $record->email,
                        'phone' => $record->phone,
                        'message' => $record->message,
                        'vous_etes' => $record->user_other
                            ? "{$record->user_type} — {$record->user_other}"
                            : $record->user_type,
                    ]),
                TableDeleteAction::make(),
            ])
            ->toolbarActions([
                // Action d'export XLSX (Excel) avec période
                Action::make('export_contacts')
                    ->label('Exporter (Excel)')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->modalHeading('Exporter les contacts')
                    ->modalSubmitActionLabel('Exporter')
                    ->form([
                        DatePicker::make('from')
                            ->label('Du')
                            ->displayFormat('d-m-Y')
                            ->native(false),
                        DatePicker::make('to')
                            ->label('Au')
                            ->displayFormat('d-m-Y')
                            ->native(false),
                    ])
                    ->action(function (array $data) {
                        $from = ! empty($data['from']) ? Carbon::parse($data['from'])->startOfDay() : null;
                        $to = ! empty($data['to']) ? Carbon::parse($data['to'])->endOfDay() : null;

                        $filename = 'contacts_' . now()->format('Ymd_His');

                        // If Laravel Excel is available, export XLSX; otherwise fallback to CSV
                        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                            return Excel::download(new ContactsExport($from, $to), $filename . '.xlsx');
                        }

                        $query = Contact::query();
                        if ($from) {
                            $query->where('created_at', '>=', $from);
                        }
                        if ($to) {
                            $query->where('created_at', '<=', $to);
                        }

                        return response()->streamDownload(function () use ($query) {
                            $out = fopen('php://output', 'w');
                            fprintf($out, "\xEF\xBB\xBF");
                            fputcsv($out, ['Nom', 'Email', 'Téléphone', 'Vous êtes', 'Autres', 'Message', 'Créé le']);
                            $query->orderBy('created_at')->chunk(500, function ($contacts) use ($out) {
                                foreach ($contacts as $contact) {
                                    $message = is_string($contact->message)
                                        ? preg_replace("/\r?\n/", ' ', $contact->message)
                                        : '';
                                    fputcsv($out, [
                                        $contact->name,
                                        $contact->email,
                                        $contact->phone,
                                        $contact->user_type,
                                        $contact->user_other,
                                        $message,
                                        optional($contact->created_at)->format('Y-m-d H:i:s'),
                                    ]);
                                }
                            });
                            fclose($out);
                        }, $filename . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
