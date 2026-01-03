<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use App\Enums\UserRole;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Vazifalar';
    protected static ?string $pluralModelLabel = 'Vazifalar';
    protected static ?string $modelLabel = 'Vazifa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Vazifalar boshqaruvi';

    public static function canViewAny(): bool
    {
        return in_array(
            auth()->user()?->role,
            [
                UserRole::SUPER->value,
                UserRole::YORDAMCHI->value,
            ]
        );
    }

    public static function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTasks::route('/'),
        ];
    }
}