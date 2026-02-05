<?php

namespace App\Filament\Resources\MyTasks\Pages;

use App\Filament\Resources\MyTasks\MyTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMyTask extends CreateRecord
{
    protected static string $resource = MyTaskResource::class;
}
