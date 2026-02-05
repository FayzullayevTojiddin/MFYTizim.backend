<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyTask;
use App\Models\Task;

class MyTaskSeeder extends Seeder
{
    public function run(): void
    {
        $taskIds = Task::pluck('id');

        if ($taskIds->isEmpty()) {
            $this->command->warn('MyTaskSeeder: task topilmadi');
            return;
        }

        foreach ($taskIds->random(min(50, $taskIds->count())) as $taskId) {
            MyTask::create([
                'task_id'     => $taskId,
                'status'      => (bool) rand(0, 1),
                'description' => collect([
                    'Vazifa to\'liq bajarildi',
                    'Hudud tozalandi va tartibga keltirildi',
                    'Ko\'rsatilgan ish o\'z vaqtida bajarildi',
                    'Aholiga xizmat ko\'rsatildi',
                    'Texnik nosozlik bartaraf etildi',
                    'Yo\'l ta\'mirlash ishlari yakunlandi',
                    'Chiroq o\'rnatildi va tekshirildi',
                    'Ariq tozalash ishlari bajarildi',
                ])->random(),
                'files'       => rand(0, 1) ? [
                    'my-tasks/example_' . rand(1, 10) . '.jpg',
                ] : null,
                'location'    => rand(0, 1) ? [
                    'lat' => 41.2995 + (rand(-100, 100) / 10000),
                    'lng' => 69.2401 + (rand(-100, 100) / 10000),
                ] : null,
            ]);
        }
    }
}