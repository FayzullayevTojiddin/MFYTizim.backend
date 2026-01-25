<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meet;
use App\Models\Neighborood;
use Illuminate\Support\Carbon;

class MeetSeeder extends Seeder
{
    public function run(): void
    {
        $neighboroodIds = Neighborood::pluck('id');

        if ($neighboroodIds->count() < 3) {
            $this->command->warn('Neighboroodlar yetarli emas (kamida 3 ta kerak)');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $meet = Meet::create([
                'title'       => "Yig‘ilish {$i}",
                'description' => "Mahalla yig‘ilishi №{$i}",
                'date_at'     => Carbon::now()
                    ->startOfMonth()
                    ->addDays(rand(0, now()->daysInMonth - 1)),
            ]);

            $randomCount = rand(2, 3);

            $randomNeighboroods = $neighboroodIds
                ->random($randomCount)
                ->toArray();

            $meet->neighboroods()->attach($randomNeighboroods);
        }
    }
}