<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meet;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Carbon;

class MeetSeeder extends Seeder
{
    public function run(): void
    {
        $workerIds = Worker::pluck('id');
        $creatorId = User::first()?->id;

        if (!$creatorId) {
            $this->command->warn('Hech qanday foydalanuvchi topilmadi');
            return;
        }

        if ($workerIds->count() < 3) {
            $this->command->warn('Ishchilar yetarli emas (kamida 3 ta kerak)');
            return;
        }

        $addresses = [
            'Hokimiyat binosi, 1-qavat',
            'Madaniyat uyi, yig\'ilishlar zali',
            'Mahalla markazi',
            'Hokimiyat binosi, 2-qavat',
            'Sport majmuasi, konferens-zal',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $meet = Meet::create([
                'user_id'     => $creatorId,
                'title'       => "Yig'ilish #{$i}",
                'description' => "Mahalla yig'ilishi №{$i}",
                'address'     => $addresses[array_rand($addresses)],
                'location'    => [
                    'lat' => 41.2995 + (rand(-100, 100) / 10000),
                    'lng' => 69.2401 + (rand(-100, 100) / 10000),
                ],
                'meet_at'     => Carbon::now()
                    ->startOfMonth()
                    ->addDays(rand(0, now()->daysInMonth - 1))
                    ->setHour(rand(9, 17))
                    ->setMinute(0),
                'status'      => ['pending', 'active', 'completed'][rand(0, 2)],
            ]);

            $randomWorkers = $workerIds->random(rand(2, min(5, $workerIds->count())));

            foreach ($randomWorkers as $workerId) {
                $meet->workers()->attach($workerId, [
                    'status'       => ['pending', 'accepted', 'declined'][rand(0, 2)],
                    'seen_at'      => rand(0, 1) ? now()->subHours(rand(1, 48)) : null,
                    'responded_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                ]);
            }
        }
    }
}