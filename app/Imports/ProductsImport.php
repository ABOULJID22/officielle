<?php

namespace App\Imports;

use App\Models\Lab;
use App\Models\Product;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $labName = self::col($row, ['labo', 'lab']);
        $productName = self::col($row, ['produit', 'product', 'name']);
        if (! $productName) {
            return null;
        }
        $lab = $labName ? Lab::firstOrCreate(['name' => $labName]) : null;

        // Avoid duplicates: upsert by lab_id + name
        $attributes = [
            'lab_id' => $lab?->id,
            'name' => trim($productName),
        ];
        $existing = Product::query()
            ->when($lab, fn($q) => $q->where('lab_id', $lab->id), fn($q) => $q->whereNull('lab_id'))
            ->where('name', $attributes['name'])
            ->first();
        if ($existing) {
            return $existing; // no update fields for now
        }
        return new Product($attributes);
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
