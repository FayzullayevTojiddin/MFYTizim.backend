<?php

namespace App\Filament\Resources\Neighboroods\Pages;

use App\Filament\Resources\Neighboroods\NeighboroodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNeighboroods extends ListRecords
{
    protected static string $resource = NeighboroodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
