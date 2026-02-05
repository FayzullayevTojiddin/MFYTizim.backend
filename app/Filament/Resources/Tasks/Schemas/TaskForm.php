<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Vazifa ma\'lumotlari')
                    ->description('Vazifa haqida asosiy ma\'lumotlar')
                    ->schema([
                        Select::make('task_category_id')
                            ->label('Kategoriya')
                            ->relationship('category', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-tag')
                            ->createOptionForm([
                                Section::make()
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Kategoriya nomi')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('status')
                                            ->label('Holat')
                                            ->options([
                                                1 => 'Faol',
                                                0 => 'Nofaol',
                                            ])
                                            ->default(1)
                                            ->required(),
                                        
                                        Textarea::make('description')
                                            ->label('Izoh')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ])
                            ->createOptionModalHeading('Yangi kategoriya yaratish')
                            ->columnSpan(2),

                        Select::make('worker_id')
                            ->label('Ishchi')
                            ->relationship('worker', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->prefixIcon('heroicon-o-hashtag'),
                    ])
                    ->columnSpan(1),

                Section::make('Muddat va holat')
                    ->description('Vazifa muddati va bajarilish holati')
                    ->schema([
                        DateTimePicker::make('deadline_at')
                            ->label('Muddat')
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-calendar'),

                        Placeholder::make('completed_at')
                            ->label('Bajarilgan vaqt')
                            ->content(fn ($record) => $record?->completed_at?->format('d.m.Y H:i') ?? 'Hali bajarilmagan')
                            ->hiddenOn('create'),
                    ])
                    ->columnSpan(1),
            ]);
    }
}