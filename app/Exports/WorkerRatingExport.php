<?php

namespace App\Exports;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkerRatingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Worker::query()
            ->with('user')
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn (Builder $q) => $q->whereNotNull('completed_at'),
                'tasks as pending_tasks' => fn (Builder $q) => $q->whereNull('completed_at'),
                'tasks as overdue_tasks' => fn (Builder $q) => $q->whereNull('completed_at')->where('deadline_at', '<', now()),
            ])
            ->orderByDesc('completed_tasks')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Ism',
            'Lavozim',
            'Jami vazifalar',
            'Bajarilgan',
            'Bajarilmagan',
            'Muddati o\'tgan',
            'Samaradorlik (%)',
        ];
    }

    public function map($worker): array
    {
        static $index = 0;
        $index++;

        $rate = $worker->total_tasks > 0
            ? round(($worker->completed_tasks / $worker->total_tasks) * 100)
            : 0;

        return [
            $index,
            $worker->user?->name ?? '-',
            $worker->title ?? '-',
            $worker->total_tasks,
            $worker->completed_tasks,
            $worker->pending_tasks,
            $worker->overdue_tasks,
            $rate,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
