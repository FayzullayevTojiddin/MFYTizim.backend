<?php

namespace App\Filament\Resources\MyTasks;

use App\Filament\Resources\MyTasks\Pages\CreateMyTask;
use App\Filament\Resources\MyTasks\Pages\EditMyTask;
use App\Filament\Resources\MyTasks\Pages\ListMyTasks;
use App\Filament\Resources\MyTasks\Schemas\MyTaskForm;
use App\Filament\Resources\MyTasks\Tables\MyTasksTable;
use App\Filament\Resources\MyTasks\Widgets\MyTaskStatsWidget;
use App\Models\MyTask;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MyTaskResource extends Resource
{
    protected static ?string $model = MyTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Boshqaruv';

    protected static ?string $navigationLabel = 'Bajarilgan ishlar';

    protected static ?string $modelLabel = 'Bajarilgan ish';

    protected static ?string $pluralModelLabel = 'Bajarilgan ishlar';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return MyTaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyTasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            MyTaskStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyTasks::route('/'),
            'create' => CreateMyTask::route('/create'),
            'edit' => EditMyTask::route('/{record}/edit'),
        ];
    }
}