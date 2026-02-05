<?php

namespace App\Filament\Resources\Meets\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class MeetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Sarlavha')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('address')
                    ->label('Manzil')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->address),

                TextColumn::make('meet_at')
                    ->label('Vaqti')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->meet_at->isPast() ? 'gray' : 'success'),

                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'active' => 'Faol',
                        'completed' => 'Yakunlangan',
                        'cancelled' => 'Bekor qilingan',
                    }),

                TextColumn::make('workers_count')
                    ->label('Chaqirilganlar')
                    ->counts('workers')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('accepted_workers_count')
                    ->label('Qabul qilgan')
                    ->counts('acceptedWorkers')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('creator.name')
                    ->label('Yaratuvchi')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Holati')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'active' => 'Faol',
                        'completed' => 'Yakunlangan',
                        'cancelled' => 'Bekor qilingan',
                    ])
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(1)
            ->recordActions([
                Action::make('open_map')
                    ->iconButton()
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->url(fn ($record) => $record->location
                        ? 'https://yandex.uz/maps/?pt=' . $record->location['lng'] . ',' . $record->location['lat'] . '&z=17&l=map'
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->location)),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('meet_at', 'desc');
    }
}