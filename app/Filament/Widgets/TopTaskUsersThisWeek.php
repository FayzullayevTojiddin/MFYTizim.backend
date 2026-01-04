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
            ->select(
                'neighborood_id',
                DB::raw('COUNT(*) as tasks_count')
            )
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])
            ->groupBy('neighborood_id')
            ->orderByRaw('tasks_count DESC'); // MUHIM
    }

    // ❌ Filament sort va id qo‘shmasligi uchun
    protected function isTableSortingEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->label('T/R')
                ->rowIndex(),

            TextColumn::make('neighborood.title')
                ->label('Mahalla Nomi'),

            TextColumn::make('tasks_count')
                ->label('Bajarilgan vazifalar soni')
                ->badge()
                ->suffix(' ta')
                ->color('primary'),
        ];
    }
}