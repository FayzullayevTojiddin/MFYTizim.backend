<?php

namespace App\Filament\Resources\Meets\Pages;

use App\Filament\Resources\Meets\MeetResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
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
            } catch (\Exception $e) {
                continue;
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
