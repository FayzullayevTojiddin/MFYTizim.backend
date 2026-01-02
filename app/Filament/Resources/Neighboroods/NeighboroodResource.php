<?php

namespace App\Filament\Resources\Neighboroods;

use App\Filament\Resources\Neighboroods\Pages\CreateNeighborood;
use App\Filament\Resources\Neighboroods\Pages\EditNeighborood;
use App\Filament\Resources\Neighboroods\Pages\ListNeighboroods;
use App\Filament\Resources\Neighboroods\Schemas\NeighboroodForm;
use App\Filament\Resources\Neighboroods\Tables\NeighboroodsTable;
use App\Models\Neighborood;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NeighboroodResource extends Resource
{
    protected static ?string $model = Neighborood::class;

    protected static ?string $navigationLabel = 'Mahallalar';

    protected static ?string $modelLabel = 'Mahalla';

    protected static ?string $pluralModelLabel = 'Mahallalar';

    protected static string | UnitEnum | null $navigationGroup = 'Tizim';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;


    public static function form(Schema $schema): Schema
    {
        return NeighboroodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NeighboroodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNeighboroods::route('/'),
        ];
    }
}
