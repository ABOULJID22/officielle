<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use App\Models\TradeOperation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientKpis extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $isClient = $user && method_exists($user, 'isClient') && $user->isClient();

        $purchasesQuery = Purchase::query();
        $tradesQuery = TradeOperation::query();
        if ($isClient) {
            $purchasesQuery->where('user_id', $user->id);
            $tradesQuery->where('user_id', $user->id);
        }

        $ordersCount = $purchasesQuery->count();
        $nextOrder = (clone $purchasesQuery)
            ->whereNotNull('next_order_date')
            ->orderBy('next_order_date')
            ->value('next_order_date');
        $activeTrades = (clone $tradesQuery)
            ->where(function ($q) {
                $q->whereNull('challenge_end')->orWhere('challenge_end', '>=', now());
            })
            ->count();

        return [
            Stat::make('Commandes totales', (string) $ordersCount),
            Stat::make('Prochaine commande', $nextOrder ? \Illuminate\Support\Carbon::parse($nextOrder)->format('d/m/Y') : '—'),
            Stat::make('Trades en cours', (string) $activeTrades),
        ];
    }

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->isClient() || $user->isSuperAdmin());
    }
}
