<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Umumiy',
                'description' => 'Umumiy vazifalar'
            ],
            [
                'title' => 'Muhim',
                'description' => 'Muhim va ustuvor vazifalar'
            ],
            [
                'title' => 'Shoshilinch',
                'description' => 'Zudlik bilan bajarilishi kerak bo‘lgan vazifalar'
            ],
            [
                'title' => 'Texnik',
                'description' => 'Texnik xizmat va sozlash ishlari'
            ],
        ];

        foreach ($categories as $category) {
            TaskCategory::create($category);
        }
    }
}