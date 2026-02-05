<?php

namespace App\Filament\Resources\Meets\Schemas;

use App\Models\Worker;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MeetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Uchrashuv ma\'lumotlari')
                    ->description('Uchrashuv haqida asosiy ma\'lumotlar')
                    ->schema([
                        TextInput::make('title')
                            ->label('Sarlavha')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-document-text'),

                        Textarea::make('description')
                            ->label('Tavsif')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Uchrashuv mavzusi haqida...'),

                        DateTimePicker::make('meet_at')
                            ->label('Uchrashuv vaqti')
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-calendar'),

                        Select::make('status')
                            ->label('Holati')
                            ->options([
                                'pending' => 'Kutilmoqda',
                                'active' => 'Faol',
                                'completed' => 'Yakunlangan',
                                'cancelled' => 'Bekor qilingan',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-flag'),
                    ])
                    ->columnSpan(1),

                Section::make('Joylashuv')
                    ->description('Uchrashuv o\'tkaziladigan joy')
                    ->schema([
                        TextInput::make('address')
                            ->label('Manzil')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-map-pin'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('location.lat')
                                    ->label('Kenglik (lat)')
                                    ->numeric()
                                    ->placeholder('41.2995'),

                                TextInput::make('location.lng')
                                    ->label('Uzunlik (lng)')
                                    ->numeric()
                                    ->placeholder('69.2401'),
                            ]),

                        Select::make('workers')
                            ->label('Ishchilarni chaqirish')
                            ->relationship('workers', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-users')
                            ->hintAction(
                                Action::make('select_all')
                                    ->label('Barchasini tanlash')
                                    ->icon('heroicon-o-user-group')
                                    ->action(function ($component) {
                                        $component->state(
                                            Worker::pluck('id')->toArray()
                                        );
                                    })
                            ),
                    ])
                    ->columnSpan(1),
            ]);
    }
}