<?php

namespace App\Imports;

use App\Models\Commercial;
use App\Models\Lab;
use App\Models\Purchase;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchasesImport implements ToModel, WithHeadingRow
{
    protected int $forUserId;

    public function __construct(int $forUserId)
    {
        $this->forUserId = $forUserId;
    }

    public function model(array $row)
    {
        $userId = $this->forUserId;

        $labName = self::col($row, ['labo', 'lab']);
        if (! $labName) {
            return null; // skip if lab is missing
        }
        $lab = Lab::firstOrCreate(['name' => (string) $labName]);
        $commercial = null;
        if ($name = self::col($row, ['nom_commerciale', 'commercial_name', 'sales_rep'])) {
            $commercial = Commercial::firstOrCreate(['name' => $name]);
        }

        return new Purchase([
            'user_id' => $userId,
            'lab_id' => $lab->id,
            'commercial_id' => $commercial?->id,
            'last_order_date' => self::dateFrom(self::col($row, ['derniere_commande', 'last_order', 'last_order_date'])),
            'last_order_value' => self::numFrom(self::col($row, ['valeur', 'value'])),
            'next_order_date' => self::dateFrom(self::col($row, ['prochaine_commande', 'next_order', 'next_order_date'])),
            'annual_target' => self::numFrom(self::col($row, ['objectif_annuel', 'annual_target'])),
            'status' => self::statusFrom(self::col($row, ['statut', 'status'])),
        ]);
    }

    protected static function dateFrom($value)
    {
        if ($value === null || $value === '') return null;
        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return \Carbon\Carbon::instance($dt);
            } catch (\Throwable) {
                // fallthrough
            }
        }
        try { return \Carbon\Carbon::parse($value); } catch (\Throwable) { return null; }
    }

    protected static function numFrom($value)
    {
        if ($value === null || $value === '') return null;
        $v = str_replace(["\xC2\xA0", ' ', '€', '$', '£'], '', (string) $value);
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? (float) $v : null;
    }

    protected static function statusFrom($value)
    {
        $v = strtolower(trim((string) $value));
        return match ($v) {
            'livrée', 'livree', 'livre', 'livré', 'delivered' => 'livree',
            'annulée', 'annulee', 'annule', 'annulé', 'canceled', 'cancelled' => 'annulee',
            'en_attente', 'pending' => 'en_attente',
            default => 'en_attente',
        };
    }

    protected static function col(array $row, array $candidates): ?string
    {
        foreach ($candidates as $key) {
            $val = Arr::get($row, $key);
            if ($val !== null && trim((string) $val) !== '') {
                return is_string($val) ? trim($val) : $val;
            }
        }
        return null;
    }
}
