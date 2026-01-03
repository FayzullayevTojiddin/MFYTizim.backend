<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NeighboroodTaskLineChart extends ChartWidget
{
    protected ?string $heading = 'Mahalla vazifalari (oxirgi 7 kun)';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;
    
    public $record;

    protected ?string $maxHeight = '800px';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $statuses = ['new', 'apply', 'cancelled'];
        $datasets = [];
        $labels = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');

            foreach ($statuses as $status) {
                $datasets[$status][] = Task::where('neighborood_id', $this->record->id)
                    ->where('status', $status)
                    ->whereDate('created_at', $date)
                    ->count();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yangi',
                    'data' => $datasets['new'],
                    'borderColor' => '#3b82f6',        // blue-500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'borderWidth' => 1.5,
                    'pointRadius' => 2,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Qabul qilingan',
                    'data' => $datasets['apply'],
                    'borderColor' => '#22c55e',        // green-500
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'borderWidth' => 1.5,
                    'pointRadius' => 2,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Bekor qilingan',
                    'data' => $datasets['cancelled'],
                    'borderColor' => '#ef4444',        // red-500
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'borderWidth' => 1.5,
                    'pointRadius' => 2,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}