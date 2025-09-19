<?php

namespace App\Imports;

use App\Models\Commercial;
use App\Models\User;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class CommercialsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row): void
    {
        $r = $row->toArray();
        $name = self::col($r, ['nom', 'name']);
        if (! $name) return;

        $contact = self::col($r, ['contact', 'telephone', 'phone']);
        $commercial = Commercial::updateOrCreate(
            ['name' => $name],
            ['contact' => $contact]
        );

        $clientsRaw = self::col($r, ['clients', 'pharmacies']);
        if ($clientsRaw) {
            $names = array_filter(array_map(fn($s) => trim($s), preg_split('/[;,|]/', (string) $clientsRaw)));
            if ($names) {
                $ids = User::query()
                    ->whereIn('name', $names)
                    ->pluck('id')
                    ->all();
                if ($ids) {
                    $commercial->clients()->syncWithoutDetaching($ids);
                }
            }
        }
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
