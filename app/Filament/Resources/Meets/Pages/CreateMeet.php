<?php

namespace App\Filament\Resources\Meets\Pages;

use App\Filament\Resources\Meets\MeetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMeet extends CreateRecord
{
    protected static string $resource = MeetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        return $data;
    }
}
