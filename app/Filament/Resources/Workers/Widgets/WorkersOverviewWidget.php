<?php

namespace App\Filament\Resources\Workers\Widgets;

use App\Models\Worker;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WorkersOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Worker::count();
        $withTasks = Worker::has('tasks')->count();
        $withoutTasks = Worker::doesntHave('tasks')->count();
        $topWorker = Worker::withCount(['tasks as completed_count' => fn ($q) => $q->whereNotNull('completed_at')])
            ->orderByDesc('completed_count')
            ->first();

        return [
            Stat::make('Jami ishchilar', $total)
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Vazifasi bor', $withTasks)
                ->description($withoutTasks . ' ta bo\'sh')
                ->icon('heroicon-o-briefcase')
                ->color('success'),

            Stat::make('Jami vazifalar', Task::count())
                ->description(Task::whereNotNull('completed_at')->count() . ' ta bajarilgan')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning'),
        ];
    }
}