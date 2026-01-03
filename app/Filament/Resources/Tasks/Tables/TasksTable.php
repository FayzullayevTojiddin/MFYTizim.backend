<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Actions\ApplyTask;
use Filament\Actions\ActionGroup;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('neighborood.title')
                    ->label('Mahalla')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('category.title')
                    ->label('Kategoriya')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'new'       => 'Yangi',
                        'apply'     => 'Tasdiqlangan',
                        'cancelled' => 'Bekor qilingan',
                        default     => ucfirst($state),
                    })
                    ->color(fn (string $state) => match ($state) {
                        'new'       => 'warning',
                        'apply'     => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->icon(fn (string $state) => match ($state) {
                        'new'       => 'heroicon-o-clock',
                        'apply'     => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default     => null,
                    }),
                TextColumn::make('created_at')
                    ->label('Yaratilgan vaqti')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Tahrirlash')
                        ->button(),
                    ApplyTask::make()
                        ->visible(fn () => auth()->user()?->role === 'super'),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('O‘chirish'),
                ]),
            ]);
    }
}