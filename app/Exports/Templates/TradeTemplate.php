<?php

namespace App\Exports\Templates;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TradeTemplate implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'labo', 'produit',
            'date_challenge_debut', 'date_challenge_fin',
            'compensation', 'type', 'envoye_le', 'recu', 'via',
        ];
    }

    public function array(): array
    {
        return [
            ['Lab X', 'Produit 1', '2025-09-01', '2025-09-30', '10', 'percent', '2025-09-01', 'oui', 'email'],
        ];
    }
}
