<?php

namespace App\Filament\Resources\Neighboroods\Pages;

use App\Filament\Resources\Neighboroods\NeighboroodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Widgets\NeighboroodTaskLineChart;

class EditNeighborood extends EditRecord
{
    protected static string $resource = NeighboroodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            NeighboroodTaskLineChart::class
        ];
    }
}
