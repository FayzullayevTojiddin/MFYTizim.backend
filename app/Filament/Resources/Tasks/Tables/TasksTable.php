<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.title')
                    ->label('Kategoriya')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('worker.title')
                    ->label('Ishchi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('worker.user.name')
                    ->label('Ishchi ismi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('count')
                    ->label('Soni')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('deadline_at')
                    ->label('Muddat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : 'default'),

                TextColumn::make('completed_at')
                    ->label('Bajarilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('Bajarilmagan'),

                IconColumn::make('status')
                    ->label('Holati')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('task_category_id')
                    ->label('Kategoriya')
                    ->relationship('category', 'title')
                    ->native(false),

                SelectFilter::make('worker_id')
                    ->label('Ishchi')
                    ->relationship('worker', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Holati')
                    ->options([
                        '1' => 'Bajarilgan',
                        '0' => 'Bajarilmagan',
                    ])
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(3)
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('deadline_at', 'asc');
    }
}