<?php

namespace App\Console\Commands;

use App\Models\Worker;
use App\Models\Task;
use Illuminate\Console\Command;

class CreateDailyAttendanceTask extends Command
{
    protected $signature = 'task:daily-attendance';
    protected $description = 'Har kuni barcha xodimlarga ishga kelish vazifasini yaratish';

    public function handle()
    {
        $workers = Worker::all();
        $today = now()->format('Y-m-d');
        $deadline = now()->format('Y-m-d') . ' 09:00:00';

        foreach ($workers as $worker) {
            $exists = Task::where('worker_id', $worker->id)
                ->where('task_category_id', 1)
                ->whereDate('created_at', $today)
                ->exists();

            if ($exists) continue;

            Task::create([
                'worker_id' => $worker->id,
                'task_category_id' => 1,
                'title' => 'Ishga kelish',
                'description' => $today . ' kuni soat 09:00 gacha ishga kelish',
                'deadline' => $deadline,
            ]);
        }
    }
}