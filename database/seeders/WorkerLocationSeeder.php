<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;
use App\Models\WorkerLocation;
use Illuminate\Support\Carbon;

class WorkerLocationSeeder extends Seeder
{
    public function run(): void
    {
        $workers = Worker::all();

        if ($workers->isEmpty()) {
            $this->command->warn('WorkerLocationSeeder: ishchilar topilmadi');
            return;
        }

        $tashkentLocations = [
            ['lat' => 41.2995, 'lng' => 69.2401, 'address' => 'Amir Temur xiyoboni'],
            ['lat' => 41.3111, 'lng' => 69.2797, 'address' => 'Chorsu bozori'],
            ['lat' => 41.3268, 'lng' => 69.2285, 'address' => 'Olmazor tumani hokimiyati'],
            ['lat' => 41.2867, 'lng' => 69.2044, 'address' => 'Sergeli tumani'],
            ['lat' => 41.3384, 'lng' => 69.3346, 'address' => 'Yashnobod tumani'],
            ['lat' => 41.3521, 'lng' => 69.2890, 'address' => 'Shayhontohur tumani'],
            ['lat' => 41.2780, 'lng' => 69.2530, 'address' => 'Mirzo Ulug\'bek tumani'],
            ['lat' => 41.3100, 'lng' => 69.2500, 'address' => 'Chilonzor tumani'],
            ['lat' => 41.3650, 'lng' => 69.2200, 'address' => 'Bektemir tumani'],
            ['lat' => 41.2900, 'lng' => 69.1800, 'address' => 'Yunusobod tumani'],
        ];

        foreach ($workers as $worker) {
            $daysBack = rand(5, 14);

            for ($day = $daysBack; $day >= 0; $day--) {
                $date = Carbon::now()->subDays($day);
                $pointsPerDay = rand(8, 12);

                for ($i = 0; $i < $pointsPerDay; $i++) {
                    $base = $tashkentLocations[array_rand($tashkentLocations)];

                    WorkerLocation::create([
                        'worker_id'     => $worker->id,
                        'latitude'      => $base['lat'] + (rand(-50, 50) / 10000),
                        'longitude'     => $base['lng'] + (rand(-50, 50) / 10000),
                        'address'       => $base['address'],
                        'accuracy'      => rand(5, 100) / 10,
                        'battery_level' => rand(10, 100),
                        'created_at'    => $date->copy()->setHour(rand(8, 18))->setMinute(rand(0, 59)),
                        'updated_at'    => $date->copy()->setHour(rand(8, 18))->setMinute(rand(0, 59)),
                    ]);
                }
            }
        }
    }
}