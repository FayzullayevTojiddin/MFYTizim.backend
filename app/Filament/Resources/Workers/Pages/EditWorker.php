<?php

namespace App\Filament\Resources\Workers\Pages;

use App\Filament\Resources\Workers\Widgets\WorkerStatsWidget;
use App\Filament\Resources\Workers\WorkerResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Kreait\Firebase\Messaging\CloudMessage;

class EditWorker extends EditRecord
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('requestLocation')
                ->label('GPS so\'rash')
                ->icon('heroicon-o-map-pin')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('GPS so\'rash')
                ->modalDescription('Ushbu xodimga GPS so\'rovi yuborilsinmi?')
                ->action(function ($record) {
                    $user = $record->user;

                    if (!$user || !$user->fcm) {
                        Notification::make()
                            ->title('FCM token topilmadi')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        $message = CloudMessage::withTarget('token', $user->fcm)
                            ->withData(['type' => 'location_request']);

                        app('firebase.messaging')->send($message);

                        Notification::make()
                            ->title('GPS so\'rovi yuborildi')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Xatolik: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WorkerStatsWidget::class,
        ];
    }
}
