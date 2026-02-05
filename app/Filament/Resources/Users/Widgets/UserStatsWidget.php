<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = User::count();
        $active = User::where('status', true)->count();
        $inactive = User::where('status', false)->count();
        $today = User::whereDate('created_at', today())->count();
        $thisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $online = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        return [
            Stat::make('Jami foydalanuvchilar', $total)
                ->description('Barcha foydalanuvchilar')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Faol', $active)
                ->description($inactive . ' ta nofaol')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Onlayn', $online)
                ->description('Hozir tizimda')
                ->icon('heroicon-o-signal')
                ->color('info'),

            Stat::make('Bugun qo\'shilgan', $today)
                ->description('Bu hafta: ' . $thisWeek)
                ->icon('heroicon-o-user-plus')
                ->color('warning'),
        ];
    }
}