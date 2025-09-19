<?php

namespace App\Exports;

use App\Models\TradeOperation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TradeOperationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected ?Authenticatable $user;
    protected ?int $forUserId = null;
    protected array $filters = [];

    public function __construct(?Authenticatable $user, ?int $forUserId = null, array $filters = [])
    {
        $this->user = $user;
        $this->forUserId = $forUserId;
        $this->filters = $filters;
    }

    public function query()
    {
        /** @var Builder $query */
    $query = TradeOperation::query()->with(['lab', 'product', 'products']);

        if ($this->forUserId) {
            return $query->where('user_id', $this->forUserId);
        }

        $user = $this->user;
        if ($user && method_exists($user, 'isClient') && $user->isClient()) {
            $query->where('user_id', $user->id);
        } elseif ($user && method_exists($user, 'isAssistant') && $user->isAssistant()) {
            $query->whereIn('user_id', function ($sub) use ($user) {
                $sub->from('commercial_user as cu')
                    ->select('cu.user_id')
                    ->join('commercials as c', 'c.id', '=', 'cu.commercial_id')
                    ->where('c.user_id', $user->id);
            });
        }

        // Apply explicit filters
        if (!empty($this->filters)) {
            if (!empty($this->filters['user_id'])) {
                $query->where('user_id', $this->filters['user_id']);
            }
            if (!empty($this->filters['lab_id'])) {
                $query->where('lab_id', $this->filters['lab_id']);
            }
            if (!empty($this->filters['product_id'])) {
                $query->where(function ($q) {
                    $q->where('product_id', $this->filters['product_id'])
                      ->orWhereHas('products', fn($qq) => $qq->where('products.id', $this->filters['product_id']));
                });
            }
            if (!empty($this->filters['start_from'])) {
                $query->whereDate('challenge_start', '>=', $this->filters['start_from']);
            }
            if (!empty($this->filters['end_to'])) {
                $query->whereDate('challenge_end', '<=', $this->filters['end_to']);
            }
            if (array_key_exists('received', $this->filters) && $this->filters['received'] !== null && $this->filters['received'] !== '') {
                $val = filter_var($this->filters['received'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                if ($val !== null) {
                    $query->where('received', $val);
                }
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'LABO',
            'PRODUIT',
            'DATE CHALLENGE',
            'COMPENSATION',
            'ENVOYÉ LE',
            'REÇU',
            'VIA',
            'NB PHOTOS',
        ];
    }

    public function map($record): array
    {
        /** @var TradeOperation $record */
        return [
            optional($record->lab)->name,
            $record->products && $record->products->count() ? $record->products->pluck('name')->implode(', ') : optional($record->product)->name,
            '(' . (optional($record->challenge_start)?->format('d-m-Y') ?? '—') . ' au ' . (optional($record->challenge_end)?->format('d-m-Y') ?? '—') . ')',
            $record->compensation_type === 'percent'
                ? ($record->compensation . ' %')
                : number_format((float) $record->compensation, 2, ',', ' ') . ' €',
            optional($record->sent_at)?->format('d/m/Y'),
            $record->received ? 'Oui' : 'Non',
            $record->via,
            is_array($record->photos) ? count($record->photos) : 0,
        ];
    }
}
