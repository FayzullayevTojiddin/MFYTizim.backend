<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Foydalanuvchilar', User::count())
                ->chart([2, 4, 6, 5, 8, 10, User::count()])
                ->color('success'),
                
            Stat::make('Vazifalar', Task::count())
                ->description('Oxirgi haftalik holat')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->chart([1, 3, 4, 6, 5, 7, Task::count()])
                ->color('info'),
        ];
    }
}