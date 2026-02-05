<?php

namespace App\Filament\Resources\Meets\Widgets;

use App\Models\Meet;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MeetStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Meet::count();
        $pending = Meet::where('status', 'pending')->count();
        $active = Meet::where('status', 'active')->count();
        $upcoming = Meet::where('meet_at', '>', now())->where('status', '!=', 'cancelled')->count();

        return [
            Stat::make('Jami uchrashuvlar', $total)
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make('Kutilmoqda', $pending)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Faol', $active)
                ->icon('heroicon-o-play')
                ->color('success'),

            Stat::make('Kelgusi', $upcoming)
                ->description('Bekor qilinmaganlar')
                ->icon('heroicon-o-arrow-right')
                ->color('info'),
        ];
    }
}