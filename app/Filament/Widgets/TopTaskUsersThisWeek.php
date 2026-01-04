<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopTaskUsersThisWeek extends BaseWidget
{
    protected static ?string $heading = 'Shu haftada eng ko‘p vazifa bajarganlar';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getTableRecordKey($record): string
    {
        return (string) $record->neighborood_id;
    }

    protected function getTableQuery(): Builder
    {
        return Task::query()
            ->select([
                'neighborood_id',

                DB::raw('COUNT(*) as tasks_count'),
                DB::raw("SUM(CASE WHEN status = 'apply' THEN 1 ELSE 0 END) as applied_count"),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_count"),
                DB::raw("SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count"),
            ])
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])
            ->groupBy('neighborood_id')
            ->orderByDesc('tasks_count');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->label('T/R')
                ->rowIndex()
                ->badge(),

            TextColumn::make('neighborood.title')
                ->label('Mahalla Nomi'),

            TextColumn::make('applied_count')
                ->label('Qabul qilingan')
                ->badge()
                ->suffix(' ta')
                ->color('success'),

            TextColumn::make('cancelled_count')
                ->label('Bekor qilingan')
                ->badge()
                ->suffix(' ta')
                ->color('danger'),

            TextColumn::make('new_count')
                ->label('Yangi')
                ->badge()
                ->suffix(' ta')
                ->color('info'),

            TextColumn::make('tasks_count')
                ->label('Jami')
                ->badge()
                ->suffix(' ta')
                ->color('primary'),
        ];
    }
}