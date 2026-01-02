<?php

namespace App\Filament\Resources\Meets;

use App\Filament\Resources\Meets\Pages\CreateMeet;
use App\Filament\Resources\Meets\Pages\EditMeet;
use App\Filament\Resources\Meets\Pages\ListMeets;
use App\Filament\Resources\Meets\Schemas\MeetForm;
use App\Filament\Resources\Meets\Tables\MeetsTable;
use App\Models\Meet;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MeetResource extends Resource
{
    protected static ?string $model = Meet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Uchrashuvlar';

    protected static ?string $modelLabel = 'Uchrashuv';

    protected static ?string $pluralModelLabel = 'Uchrashuvlar';

    protected static string | UnitEnum | null $navigationGroup = 'Uchrashuvlar boshqaruvi';

    public static function form(Schema $schema): Schema
    {
        return MeetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MeetsTable::configure($table);
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
            'index' => ListMeets::route('/'),
            'create' => CreateMeet::route('/create'),
            'edit' => EditMeet::route('/{record}/edit'),
        ];
    }
}
