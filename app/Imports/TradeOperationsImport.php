<?php

namespace App\Imports;

use App\Models\Lab;
use App\Models\Product;
use App\Models\TradeOperation;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TradeOperationsImport implements ToModel, WithHeadingRow
{
    protected int $forUserId;

    public function __construct(int $forUserId)
    {
        $this->forUserId = $forUserId;
    }

    public function model(array $row)
    {
        $userId = $this->forUserId;

        $lab = Lab::firstOrCreate(['name' => (string) self::col($row, ['labo', 'lab'])]);
        $product = null;
        if ($p = self::col($row, ['produit', 'product'])) {
            $product = Product::firstOrCreate(['name' => $p, 'lab_id' => $lab->id]);
        }

        return new TradeOperation([
            'user_id' => $userId,
            'lab_id' => $lab->id,
            'product_id' => $product?->id,
            'challenge_start' => self::dateFrom(self::col($row, ['date_challenge_debut', 'challenge_start', 'date_start'])),
            'challenge_end' => self::dateFrom(self::col($row, ['date_challenge_fin', 'challenge_end', 'date_end'])),
            'compensation' => self::numFrom(self::col($row, ['compensation'])),
            'compensation_type' => self::typeFrom(self::col($row, ['type'])),
            'sent_at' => self::dateFrom(self::col($row, ['envoye_le', 'sent_at'])),
            'received' => self::boolFrom(self::col($row, ['recu', 'received'])),
            'via' => self::col($row, ['via']),
        ]);
    }

    protected static function dateFrom($value)
    {
        if ($value === null || $value === '') return null;
        // Excel serial date support
        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return \Carbon\Carbon::instance($dt);
            } catch (\Throwable) {
                // fallthrough to parse
            }
        }
        try { return \Carbon\Carbon::parse($value); } catch (\Throwable) { return null; }
    }

    protected static function numFrom($value)
    {
        if ($value === null || $value === '') return null;
        // Remove non-breaking spaces and currency symbols
        $v = str_replace(["\xC2\xA0", ' ', '€', '$', '£'], '', (string) $value);
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? (float) $v : null;
    }

    protected static function typeFrom($value)
    {
        $v = strtolower(trim((string) $value));
        return in_array($v, ['percent', 'pourcentage']) ? 'percent' : 'amount';
    }

    protected static function boolFrom($value)
    {
        $v = strtolower(trim((string) $value));
        return in_array($v, ['1','oui','yes','true','vrai']);
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
