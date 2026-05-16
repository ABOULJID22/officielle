<?php

namespace App\Exports\Templates;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplate implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['labo', 'produit'];
    }

    public function array(): array
    {
        return [
            ['Lab X', 'Produit 1'],
        ];
    }
}
