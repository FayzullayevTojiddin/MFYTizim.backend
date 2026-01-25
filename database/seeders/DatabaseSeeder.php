<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => "Super Admin",
            'email' => "super@mfy.uz",
            'password' => "As123456",
            'role' => "super"
        ]);

        $this->call([
            TaskCategorySeeder::class,
            NeighboroodWithUserSeeder::class,
            MeetSeeder::class
        ]);
    }
}
