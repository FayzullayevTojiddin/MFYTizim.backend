<?php

namespace App\Filament\Resources\Meets\Pages;

use App\Filament\Resources\Meets\MeetResource;
use App\Filament\Resources\Meets\Widgets\MeetStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMeets extends ListRecords
{
    protected static string $resource = MeetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MeetStatsWidget::class,
        ];
    }
}
