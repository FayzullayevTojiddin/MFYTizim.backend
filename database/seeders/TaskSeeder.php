<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Neighborood;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds   = TaskCategory::pluck('id');
        $neighboroodIds = Neighborood::pluck('id');

        if ($categoryIds->isEmpty() || $neighboroodIds->isEmpty()) {
            $this->command->warn('TaskSeeder: category yoki neighborood topilmadi');
            return;
        }

        $totalTasks = 200;

        for ($i = 1; $i <= $totalTasks; $i++) {

            Task::create([
                'task_category_id' => $categoryIds->random(),
                'neighborood_id'   => $neighboroodIds->random(),
                'description'      => "Avtomatik yaratilgan vazifa #{$i}",
                'status'           => collect(['new', 'in_progress', 'done'])->random(),
                'file'             => null,
                'latitude'         => 41.3 + rand(-1000, 1000) / 10000,
                'longitude'        => 69.2 + rand(-1000, 1000) / 10000,
            ]);
        }
    }
}