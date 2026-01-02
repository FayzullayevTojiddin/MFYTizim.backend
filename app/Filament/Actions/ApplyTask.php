<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class ApplyTask
{
    public static function make(): Action
    {
        return Action::make('apply-toggle')
            ->label(fn ($record) =>
                $record->status === 'apply'
                    ? 'Bekor qilish'
                    : 'Tasdiqlash'
            )
            ->icon(fn ($record) =>
                $record->status === 'apply'
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle'
            )
            ->color(fn ($record) =>
                $record->status === 'apply'
                    ? 'danger'
                    : 'success'
            )
            ->button()
            ->requiresConfirmation()
            ->modalHeading(fn ($record) =>
                $record->status === 'apply'
                    ? 'Bekor qilishni tasdiqlash'
                    : 'Tasdiqlashni tasdiqlash'
            )
            ->modalDescription(fn ($record) =>
                $record->status === 'apply'
                    ? 'Ushbu vazifani bekor qilmoqchimisiz?'
                    : 'Ushbu vazifani tasdiqlamoqchimisiz?'
            )
            ->action(function ($record) {
                $record->update([
                    'status' => $record->status === 'apply'
                        ? 'cancelled'
                        : 'apply',
                ]);
            });
    }
}