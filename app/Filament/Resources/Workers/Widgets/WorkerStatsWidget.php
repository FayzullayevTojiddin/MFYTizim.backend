<?php

namespace App\Filament\Resources\Workers\Widgets;

use App\Models\Worker;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class WorkerStatsWidget extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $total = $this->record->tasks()->count();
        $completed = $this->record->completedTasks()->count();
        $pending = $this->record->pendingTasks()->count();
        $overdue = $this->record->tasks()
            ->whereNull('completed_at')
            ->where('deadline_at', '<', now())
            ->count();

        return [
            Stat::make('Jami vazifalar', $total)
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make('Bajarilgan', $completed)
                ->description($total > 0 ? round(($completed / $total) * 100) . '%' : '0%')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Bajarilmagan', $pending)
                ->description($overdue > ' muddati o\'tgan')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Muddati o\'tgan', $overdue)
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}