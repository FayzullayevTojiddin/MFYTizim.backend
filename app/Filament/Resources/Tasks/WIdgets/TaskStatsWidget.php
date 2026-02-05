<?php

namespace App\Filament\Resources\Tasks\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Task::count();
        $completed = Task::whereNotNull('completed_at')->count();
        $pending = Task::whereNull('completed_at')->count();
        $overdue = Task::whereNull('completed_at')
            ->where('deadline_at', '<', now())
            ->count();

        return [
            Stat::make('Jami', $total)
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make('Bajarilgan', $completed)
                ->description($total > 0 ? round(($completed / $total) * 100) . '%' : '0%')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Bajarilmagan', $pending)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Muddati o\'tgan', $overdue)
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}