<?php

namespace App\Exports;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContactsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    public function __construct(
        protected ?Carbon $from = null,
        protected ?Carbon $to = null,
    ) {
    }

    public function query(): Builder
    {
        return Contact::query()
            ->when($this->from, fn (Builder $q) => $q->where('created_at', '>=', $this->from))
            ->when($this->to, fn (Builder $q) => $q->where('created_at', '<=', $this->to))
            ->orderBy('created_at');
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Email',
            'Téléphone',
            'Vous êtes',
            'Autres',
            'Message',
            'Créé le',
        ];
    }

    /**
     * @param \App\Models\Contact $contact
     */
    public function map($contact): array
    {
        return [
            $contact->name,
            $contact->email,
            $contact->phone,
            $contact->user_type,
            $contact->user_other,
            $contact->message,
            $contact->created_at, // keep as DateTimeInterface for Excel formatting
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => 'yyyy-mm-dd hh:mm:ss',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Bold header row
                $sheet->getStyle('A1:G1')->getFont()->setBold(true);

                // Wrap text for Message column and align top for all
                $sheet->getStyle('F:F')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A:G')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            },
        ];
    }
}
