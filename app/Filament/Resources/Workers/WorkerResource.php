<?php

namespace App\Filament\Resources\Workers;

use App\Filament\Resources\Workers\Pages\CreateWorker;
use App\Filament\Resources\Workers\Pages\EditWorker;
use App\Filament\Resources\Workers\Pages\ListWorkers;
use App\Filament\Resources\Workers\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\Workers\RelationManagers\TasksRelationManager;
use App\Filament\Resources\Workers\Schemas\WorkerForm;
use App\Filament\Resources\Workers\Tables\WorkersTable;
use App\Filament\Resources\Workers\Widgets\WorkersOverviewWidget;
use App\Filament\Resources\Workers\Widgets\WorkerStatsWidget;
use App\Models\Worker;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|UnitEnum|null $navigationGroup = 'Boshqaruv';

    protected static ?string $navigationLabel = 'Ishchilar';

    protected static ?string $modelLabel = 'Ishchi';

    protected static ?string $pluralModelLabel = 'Ishchilar';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return WorkerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
            LocationsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkers::route('/'),
            'create' => CreateWorker::route('/create'),
            'edit' => EditWorker::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WorkerStatsWidget::class,
            WorkersOverviewWidget::class
        ];
    }
}