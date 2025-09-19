<?php

namespace App\Exports\Templates;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommercialTemplate implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nom', 'contact', 'clients'];
    }

    public function array(): array
    {
        return [
            ['John Doe', '+212612345678', 'Pharmacie A; Pharmacie B'],
        ];
    }
}
