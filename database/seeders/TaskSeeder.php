<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Worker;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = TaskCategory::pluck('id');
        $workerIds = Worker::pluck('id');

        if ($categoryIds->isEmpty() || $workerIds->isEmpty()) {
            $this->command->warn('TaskSeeder: category yoki worker topilmadi');
            return;
        }

        for ($i = 1; $i <= 200; $i++) {
            $isCompleted = (bool) rand(0, 1);
            $deadlineAt = Carbon::now()
                ->subDays(rand(-30, 30))
                ->setHour(rand(9, 18))
                ->setMinute(0);

            Task::create([
                'task_category_id' => $categoryIds->random(),
                'worker_id'        => $workerIds->random(),
                'deadline_at'      => $deadlineAt,
                'completed_at'     => $isCompleted ? $deadlineAt->copy()->subHours(rand(1, 48)) : null,
                'count'            => rand(1, 50),
                'status'           => $isCompleted,
            ]);
        }
    }
}