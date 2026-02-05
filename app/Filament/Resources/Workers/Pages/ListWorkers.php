<?php

namespace App\Filament\Resources\Workers\Pages;

use App\Filament\Resources\Workers\Widgets\WorkersOverviewWidget;
use App\Filament\Resources\Workers\WorkerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkers extends ListRecords
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WorkersOverviewWidget::class,
        ];
    }
}
