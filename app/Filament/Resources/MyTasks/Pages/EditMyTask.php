<?php

namespace App\Filament\Resources\MyTasks\Pages;

use App\Filament\Resources\MyTasks\MyTaskResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMyTask extends EditRecord
{
    protected static string $resource = MyTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggle_status')
                ->label(fn () => $this->record->status ? 'Bekor qilish' : 'Tasdiqlash')
                ->icon(fn () => $this->record->status ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn () => $this->record->status ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->status ? 'Tasdiqlashni bekor qilish' : 'Ishni tasdiqlash')
                ->modalDescription(fn () => $this->record->status
                    ? 'Haqiqatan ham tasdiqlashni bekor qilmoqchimisiz?'
                    : 'Haqiqatan ham bu ishni tasdiqlamoqchimisiz?')
                ->modalSubmitActionLabel(fn () => $this->record->status ? 'Ha, bekor qilish' : 'Ha, tasdiqlash')
                ->action(function () {
                    $this->record->update([
                        'status' => !$this->record->status,
                    ]);

                    if ($this->record->status) {
                        $this->record->task?->update([
                            'completed_at' => now(),
                            'status' => true,
                        ]);
                    } else {
                        $this->record->task?->update([
                            'completed_at' => null,
                            'status' => false,
                        ]);
                    }

                    $this->refreshFormData(['status']);
                }),

            DeleteAction::make(),
        ];
    }
}