<?php

namespace App\Filament\Resources\MyTasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class MyTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.category.title')
                    ->label('Kategoriya')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('task.worker.title')
                    ->label('Ishchi lavozimi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Tavsif')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable(),

                IconColumn::make('status')
                    ->label('Tasdiqlangan')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Yuborilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('task_id')
                    ->label('Vazifa')
                    ->relationship('task.category', 'title')
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Holati')
                    ->options([
                        '1' => 'Tasdiqlangan',
                        '0' => 'Tasdiqlanmagan',
                    ])
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(2)
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}