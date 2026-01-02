<?php

namespace App\Filament\Resources\Meets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MeetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Uchrashuv maʼlumotlari')
                    ->schema([
                        TextInput::make('title')
                            ->label('Uchrashuv nomi')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Tavsif')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Mahallalar')
                    ->schema([
                        Select::make('neighboroods')
                            ->label('Ishtirok etuvchi mahallalar')
                            ->relationship(
                                name: 'neighboroods',
                                titleAttribute: 'title'
                            )   
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),

                        DateTimePicker::make('date_at')
                            ->label('Uchrashuv sanasi')
                            ->required(),
                    ]),
            ]);
    }
}