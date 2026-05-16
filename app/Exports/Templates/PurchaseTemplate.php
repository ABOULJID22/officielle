<?php

namespace App\Exports\Templates;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseTemplate implements FromArray, WithHeadings
{
    public function headings(): array 
    {
        return [
            'labo', 'nom_commerciale',
            'derniere_commande', 'valeur', 'prochaine_commande', 'objectif_annuel', 'statut',
        ];
    }

    public function array(): array
    {
        return [
            ['Lab X', 'John Doe', '2025-09-10', '1500', '2025-09-17', '20000', 'en_attente'],
        ];
    }
}
