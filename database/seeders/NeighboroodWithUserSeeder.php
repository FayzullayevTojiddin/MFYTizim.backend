<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Neighborood;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class NeighboroodWithUserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 51; $i++) {
            $user = User::create([
                'name' => "Hokim yordamchisi {$i}",
                'email' => "neighborood{$i}@example.com",
                'password' => Hash::make('password'),
            ]);

            Worker::create([
                'title'   => "Mahalla {$i}",
                'user_id' => $user->id,
            ]);
        }
    }
}