<?php

namespace App\Filament\Resources\Neighboroods\Pages;

use App\Filament\Resources\Neighboroods\NeighboroodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNeighborood extends EditRecord
{
    protected static string $resource = NeighboroodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
