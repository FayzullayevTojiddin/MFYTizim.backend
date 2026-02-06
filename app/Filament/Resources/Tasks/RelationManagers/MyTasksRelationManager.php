<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class MyTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'myTasks';

    protected static ?string $title = 'Yuborilgan ishlar';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                TextColumn::make('files_count')
                    ->label('Fayllar')
                    ->getStateUsing(fn ($record) => is_array($record->files) ? count($record->files) : 0)
                    ->suffix(' ta')
                    ->badge()
                    ->color('info'),

                IconColumn::make('status')
                    ->label('Holat')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                IconColumn::make('has_location')
                    ->label('GPS')
                    ->icon('heroicon-o-map-pin')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->getStateUsing(fn ($record) => $record->location !== null),

                TextColumn::make('description')
                    ->label('Izoh')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Yuborilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        '1' => 'Tasdiqlangan',
                        '0' => 'Kutilmoqda',
                    ]),
            ])
            ->actions([
                // Joylashuvni ko'rish
                Action::make('viewLocation')
                    ->label('Manzil')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->url(fn ($record) => $record->location
                        ? "https://www.google.com/maps?q={$record->location['lat']},{$record->location['lng']}"
                        : null
                    )
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->location !== null),

                // Tasdiqlash
                Action::make('approve')
                    ->label('Tasdiqlash')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tasdiqlash')
                    ->modalDescription('Ushbu ishni tasdiqlaysizmi?')
                    ->action(fn ($record) => $record->update(['status' => true]))
                    ->visible(fn ($record) => !$record->status),

                // Bekor qilish
                Action::make('reject')
                    ->label('Bekor qilish')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Bekor qilish')
                    ->modalDescription('Tasdiqlashni bekor qilasizmi?')
                    ->action(fn ($record) => $record->update(['status' => false]))
                    ->visible(fn ($record) => $record->status),

                // O'chirish
                DeleteAction::make()
                    ->label('O\'chirish'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('approveAll')
                        ->label('Tasdiqlash')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => true])),

                    BulkAction::make('rejectAll')
                        ->label('Bekor qilish')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => false])),

                    DeleteBulkAction::make()
                        ->label('O\'chirish'),
                ]),
            ]);
    }
}