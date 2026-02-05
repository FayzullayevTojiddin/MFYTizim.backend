<?php

namespace App\Filament\Resources\MyTasks\Widgets;

use App\Models\MyTask;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MyTaskStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = MyTask::count();
        $approved = MyTask::where('status', true)->count();
        $pending = MyTask::where('status', false)->count();
        $today = MyTask::whereDate('created_at', today())->count();
        $withFiles = MyTask::whereNotNull('files')->count();

        return [
            Stat::make('Jami ishlar', $total)
                ->icon('heroicon-o-document-check')
                ->color('primary'),

            Stat::make('Tasdiqlangan', $approved)
                ->description($total > 0 ? round(($approved / $total) * 100) . '%' : '0%')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tasdiqlanmagan', $pending)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Bugun yuborilgan', $today)
                ->description($withFiles . ' ta faylli')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),
        ];
    }
}