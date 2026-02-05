<?php

namespace App\Filament\Resources\MyTasks\Pages;

use App\Filament\Resources\MyTasks\MyTaskResource;
use App\Filament\Resources\MyTasks\Widgets\MyTaskStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMyTasks extends ListRecords
{
    protected static string $resource = MyTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            MyTaskStatsWidget::class,
        ];
    }
}
