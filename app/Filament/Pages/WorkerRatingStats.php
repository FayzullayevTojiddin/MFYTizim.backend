<?php

namespace App\Filament\Pages;

use App\Models\Worker;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class WorkerRatingStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $stats = Cache::remember('worker_rating_stats', 3600, function () {
            $topWorker = Worker::with('user')
                ->withCount(['tasks as completed_count' => fn (Builder $q) => $q->whereNotNull('completed_at')])
                ->orderByDesc('completed_count')
                ->first();

            $totalCompleted = Task::whereNotNull('completed_at')->count();
            $totalTasks = Task::count();

            $mostOverdue = Worker::with('user')
                ->withCount(['tasks as overdue_count' => fn (Builder $q) => $q->whereNull('completed_at')->where('deadline_at', '<', now())])
                ->orderByDesc('overdue_count')
                ->first();

            return [
                'top_name' => $topWorker?->user?->name ?? '-',
                'top_count' => $topWorker?->completed_count ?? 0,
                'total_completed' => $totalCompleted,
                'total_tasks' => $totalTasks,
                'avg_rate' => $totalTasks > 0 ? round(($totalCompleted / $totalTasks) * 100) : 0,
                'worker_count' => Worker::count(),
                'active_count' => Worker::has('tasks')->count(),
                'overdue_name' => $mostOverdue?->user?->name ?? '-',
                'overdue_count' => $mostOverdue?->overdue_count ?? 0,
            ];
        });

        return [
            Stat::make('🥇 Eng faol ishchi', $stats['top_name'])
                ->description($stats['top_count'] . ' ta bajarilgan vazifa')
                ->color('success'),

            Stat::make('Umumiy samaradorlik', $stats['avg_rate'] . '%')
                ->description($stats['total_completed'] . ' / ' . $stats['total_tasks'] . ' vazifa')
                ->color($stats['avg_rate'] >= 70 ? 'success' : ($stats['avg_rate'] >= 40 ? 'warning' : 'danger')),

            Stat::make('Jami ishchilar', $stats['worker_count'])
                ->description($stats['active_count'] . ' ta faol')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('⚠️ Eng ko\'p kechikkan', $stats['overdue_name'])
                ->description($stats['overdue_count'] . ' ta muddati o\'tgan')
                ->color('danger'),
        ];
    }
}