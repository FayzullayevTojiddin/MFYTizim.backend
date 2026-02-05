<?php

namespace App\Filament\Resources\Tasks\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class SingleTaskStatsWidget extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $totalMyTasks = $this->record->myTasks()->count();
        $approved = $this->record->myTasks()->where('status', true)->count();
        $pending = $this->record->myTasks()->where('status', false)->count();
        $withFiles = $this->record->myTasks()->whereNotNull('files')->count();
        $isOverdue = $this->record->isOverdue();
        $deadline = $this->record->deadline_at;

        return [
            Stat::make('Yuborilgan ishlar', $totalMyTasks)
                ->icon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Tasdiqlangan', $approved)
                ->description($totalMyTasks > 0 ? round(($approved / $totalMyTasks) * 100) . '%' : '0%')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tasdiqlanmagan', $pending)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Muddat', $deadline?->format('d.m.Y H:i') ?? '-')
                ->description(
                    $isOverdue
                        ? $deadline?->diffForHumans() . ' muddati o\'tgan'
                        : ($deadline?->isFuture()
                            ? $deadline->diffForHumans(syntax: true) . ' qoldi'
                            : ($deadline ? 'Muddat tugagan' : ''))
                )
                ->icon($isOverdue ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-calendar')
                ->color($isOverdue ? 'danger' : ($deadline?->isFuture() ? 'info' : 'gray')),
        ];
    }
}