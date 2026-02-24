<?php

namespace App\Filament\Resources\Meets\Pages;

use App\Filament\Resources\Meets\MeetResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Kreait\Firebase\Messaging\CloudMessage;

class EditMeet extends EditRecord
{
    protected static string $resource = MeetResource::class;

    private array $oldWorkerIds = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->oldWorkerIds = $this->record->workers()->pluck('workers.id')->toArray();
    }

    protected function afterSave(): void
    {
        $meet = $this->record;
        $newWorkerIds = $meet->workers()->pluck('workers.id')->toArray();

        // Faqat yangi qo'shilgan worker larga FCM yuborish
        $addedWorkerIds = array_diff($newWorkerIds, $this->oldWorkerIds);

        if (empty($addedWorkerIds)) {
            return;
        }

        $workers = $meet->workers()->whereIn('workers.id', $addedWorkerIds)->with('user')->get();

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
                ->title("{$sentCount} ta yangi ishchiga uchrashuv bildirish yuborildi")
                ->success()
                ->send();
        }
    }
}
