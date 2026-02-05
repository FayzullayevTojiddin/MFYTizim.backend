<?php

namespace App\Filament\Resources\Workers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class WorkersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.image')
                    ->label('Rasm')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name) . '&background=random'),

                TextColumn::make('user.name')
                    ->label('Ism')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('title')
                    ->label('Lavozim')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('completed_tasks_count')
                    ->label('Bajarilgan')
                    ->counts(['tasks as completed_tasks_count' => fn ($query) => $query->whereNotNull('completed_at')])
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('pending_tasks_count')
                    ->label('Bajarilmagan')
                    ->counts(['tasks as pending_tasks_count' => fn ($query) => $query->whereNull('completed_at')])
                    ->sortable()
                    ->badge()
                    ->color('danger'),

                TextColumn::make('tasks_count')
                    ->label('Jami')
                    ->counts('tasks')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Qo\'shilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}