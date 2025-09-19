<?php

namespace App\Exports;

use App\Models\Purchase;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchasesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Purchase::query()
            ->with(['lab', 'commercial']);

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

        // Apply explicit filters (admin can specify; clients still scoped above)
        if (!empty($this->filters)) {
            if (!empty($this->filters['user_id'])) {
                $query->where('user_id', $this->filters['user_id']);
            }
            if (!empty($this->filters['lab_id'])) {
                $query->where('lab_id', $this->filters['lab_id']);
            }
            if (!empty($this->filters['status'])) {
                $query->where('status', $this->filters['status']);
            }
            if (!empty($this->filters['last_order_from'])) {
                $query->whereDate('last_order_date', '>=', $this->filters['last_order_from']);
            }
            if (!empty($this->filters['last_order_to'])) {
                $query->whereDate('last_order_date', '<=', $this->filters['last_order_to']);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'LABO',
            'NOM COMMERCIALE',
            'DERNIERE COMMANDE',
            'VALEUR €',
            'PROCHAINE COMMANDE',
            'OBJECTIF ANNUEL',
            'STATUT',
        ];
    }

    public function map($purchase): array
    {
        /** @var Purchase $purchase */
        return [
            optional($purchase->lab)->name,
            optional($purchase->commercial)->name,
            optional($purchase->last_order_date)?->format('d/m/Y'),
            number_format((float) $purchase->last_order_value, 2, ',', ' '),
            optional($purchase->next_order_date)?->format('d/m/Y'),
            number_format((float) $purchase->annual_target, 2, ',', ' '),
            match ($purchase->status) {
                'en_attente' => 'En attente',
                'livree' => 'Livrée',
                'annulee' => 'Annulée',
                default => (string) $purchase->status,
            },
        ];
    }
}
