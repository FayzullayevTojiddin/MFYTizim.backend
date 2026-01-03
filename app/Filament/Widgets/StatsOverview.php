<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $fromDate = Carbon::now()->subDays(6);

        $allTasks = Task::where('created_at', '>=', $fromDate)->count();

        $newTasks = Task::where('status', 'new')
            ->where('created_at', '>=', $fromDate)
            ->count();

        $cancelledTasks = Task::where('status', 'cancelled')
            ->where('created_at', '>=', $fromDate)
            ->count();

        $appliedTasks = Task::where('status', 'apply')
            ->where('created_at', '>=', $fromDate)
            ->count();

        $weeklyChart = function ($status = null) use ($fromDate) {
            $query = Task::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
                ->where('created_at', '>=', $fromDate);

            if ($status) {
                $query->where('status', $status);
            }

            return $query
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count')
                ->toArray();
        };

        return [
            Stat::make('Barcha vazifalar', $allTasks)
                ->description('Oxirgi 7 kun')
                ->chart($weeklyChart())
                ->color('primary'),

            Stat::make('Yangi vazifalar', $newTasks)
                ->description('Status: Jarayonda')
                ->chart($weeklyChart('new'))
                ->color('success'),

            Stat::make('Bekor Qilingan Vazifalar', $cancelledTasks)
                ->description('Status: Bekor Qilinganlar')
                ->chart($weeklyChart('cancelled'))
                ->color('danger'),

            Stat::make('Qabul Qilingan Vazifalar', $appliedTasks)
                ->description('Status: Qabul Qilinganlar')
                ->chart($weeklyChart('apply'))
                ->color('info'),
        ];
    }
}