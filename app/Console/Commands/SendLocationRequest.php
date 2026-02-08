<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Kreait\Firebase\Messaging\CloudMessage;

class SendLocationRequest extends Command
{
    protected $signature = 'location:request';
    protected $description = 'Barcha xodimlarga GPS so\'rovi yuborish';

    public function handle()
    {
        $users = User::whereNotNull('fcm')
            ->where('fcm', '!=', '')
            ->whereHas('worker')
            ->get();

        foreach ($users as $user) {
            try {
                $message = CloudMessage::withTarget('token', $user->fcm)
                    ->withData(['type' => 'scheduled_location']);

                app('firebase.messaging')->send($message);
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->info("{$users->count()} ta xodimga GPS so'rovi yuborildi.");
    }
}