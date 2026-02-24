<?php

namespace App\Filament\Resources\Meets\Pages;

use App\Filament\Resources\Meets\MeetResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Messaging\CloudMessage;

class CreateMeet extends CreateRecord
{
    protected static string $resource = MeetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $meet = $this->record;

        $workers = $meet->workers()->with('user')->get();

        $sentCount = 0;

        foreach ($workers as $worker) {
            $user = $worker->user;

            if (!$user || !$user->fcm) {
                continue;
            }

            try {
                $message = CloudMessage::withTarget('token', $user->fcm)
                    ->withNotification([
                        'title' => 'Yangi uchrashuv: ' . $meet->title,
                        'body' => $meet->meet_at->format('d.m.Y H:i') . ' — ' . $meet->address,
                        'sound' => 'default',
                    ])
                    ->withData([
                        'type' => 'meet_invite',
                        'meet_id' => (string) $meet->id,
                        'title' => 'Yangi uchrashuv: ' . $meet->title,
                        'body' => $meet->meet_at->format('d.m.Y H:i') . ' — ' . $meet->address,
                        'description' => $meet->description ?? '',
                        'address' => $meet->address ?? '',
                        'meet_time' => $meet->meet_at->format('H:i'),
                    ])
                    ->withHighestPossiblePriority();

                app('firebase.messaging')->send($message);
                $sentCount++;

                Log::info("Meet FCM yuborildi", [
                    'meet_id' => $meet->id,
                    'worker_id' => $worker->id,
                    'user_name' => $user->name,
                ]);
            } catch (NotFound | InvalidMessage $e) {
                Log::warning("Meet FCM — token eskirgan, tozalandi", [
                    'meet_id' => $meet->id,
                    'worker_id' => $worker->id,
                    'error' => $e->getMessage(),
                ]);
                $user->update(['fcm' => null]);
            } catch (\Exception $e) {
                Log::error("Meet FCM — xatolik", [
                    'meet_id' => $meet->id,
                    'worker_id' => $worker->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($sentCount > 0) {
            Notification::make()
                ->title("{$sentCount} ta ishchiga uchrashuv bildirish yuborildi")
                ->success()
                ->send();
        }
    }
}
