<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Neighborood;
use Illuminate\Support\Facades\Hash;

class NeighboroodWithUserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 51; $i++) {
            $user = User::create([
                'name' => "Mahalla raisi {$i}",
                'email' => "neighborood{$i}@example.com",
                'password' => Hash::make('password'),
            ]);

            Neighborood::create([
                'title'   => "Mahalla {$i}",
                'user_id' => $user->id,
            ]);
        }
    }
}