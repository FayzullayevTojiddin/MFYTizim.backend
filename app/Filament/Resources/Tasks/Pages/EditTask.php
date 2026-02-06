<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use App\Filament\Resources\Tasks\Widgets\SingleTaskStatsWidget;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('toggle_status')
                ->label(fn () => $this->record->status ? 'Bekor qilish' : 'Tasdiqlash')
                ->icon(fn () => $this->record->status ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn () => $this->record->status ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->status ? 'Tasdiqlashni bekor qilish' : 'Vazifani tasdiqlash')
                ->modalDescription(fn () => $this->record->status
                    ? 'Haqiqatan ham tasdiqlashni bekor qilmoqchimisiz?'
                    : 'Haqiqatan ham bu vazifani tasdiqlamoqchimisiz?')
                ->modalSubmitActionLabel(fn () => $this->record->status ? 'Ha, bekor qilish' : 'Ha, tasdiqlash')
                ->action(function () {
                    $newStatus = !$this->record->status;

                    $this->record->update([
                        'status' => $newStatus,
                        'completed_at' => $newStatus ? now() : null,
                    ]);

                    $this->record->myTasks()->update([
                        'status' => $newStatus,
                    ]);

                    $this->refreshFormData(['status', 'completed_at']);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SingleTaskStatsWidget::class,
        ];
    }
}
