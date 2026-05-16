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
            ->with(['lab', 'commercial', 'user']);

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
            'Nom_Labo',
            'Catégorie',
            'Pharmacie',
            'Nom commerciale',
            'Contact',
            'Date dernière commande',
            'Valeur €',
            'Prochaine commande',
            'Objectif annuel',
           
        ];
    }

    public function map($purchase): array
    {
        /** @var Purchase $purchase */
        return [
            // Lab name
            optional($purchase->lab)->name,
            // Lab category name (if relation exists on Purchase as labCategory)
            $purchase->labCategory?->name ?? '',
            // Pharmacy display name (pharmacy_name or user name)
            optional($purchase->user)->pharmacy_name ?: optional($purchase->user)->name ?: '',
            // Commercial name
            optional($purchase->commercial)->name ?: '',
            // Commercial contact
            optional($purchase->commercial)->contact ?: '',
            // Last order date
            optional($purchase->last_order_date)?->format('d/m/Y') ?: '',
            // Last order value
            $purchase->last_order_value !== null ? number_format((float) $purchase->last_order_value, 2, ',', ' ') : '',
            // Next order date
            optional($purchase->next_order_date)?->format('d/m/Y') ?: '',
            // Annual target
            $purchase->annual_target !== null ? number_format((float) $purchase->annual_target, 2, ',', ' ') : '',
        ];
    }
}
