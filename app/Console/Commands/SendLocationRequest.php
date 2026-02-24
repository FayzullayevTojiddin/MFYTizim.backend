<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;

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

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            try {
                $message = CloudMessage::withTarget('token', $user->fcm)
                    ->withData(['type' => 'scheduled_location'])
                    ->withHighestPossiblePriority();

                app('firebase.messaging')->send($message);
                $sentCount++;
            } catch (NotFound | InvalidMessage $e) {
                // Token eskirgan yoki noto'g'ri — bazadan tozalash
                Log::warning("FCM token eskirgan — tozalandi", [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                ]);
                $user->update(['fcm' => null]);
                $failedCount++;
            } catch (\Exception $e) {
                Log::error("FCM yuborishda xatolik", [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
            }
        }

        $this->info("GPS so'rovi: {$sentCount} ta yuborildi, {$failedCount} ta xatolik.");
        Log::info("GPS so'rovi: {$sentCount} yuborildi, {$failedCount} xatolik, jami: {$users->count()}");
    }
}