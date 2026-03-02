<?php

namespace App\Filament\Resources\Meets\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MeetWorkersRelationManager extends RelationManager
{
    protected static string $relationship = 'workers';

    protected static ?string $title = 'Chaqirilgan ishchilar';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Ism')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Lavozim')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('pivot.status')
                    ->label('Holati')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->pivot->status ?? 'pending')
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'accepted' => 'Qabul qildi',
                        'rejected' => 'Rad etdi',
                        default => $state,
                    }),

                IconColumn::make('pivot.seen_at')
                    ->label('Ko\'rganmi')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->pivot->seen_at !== null)
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('seen_at_text')
                    ->label('Ko\'rgan vaqti')
                    ->getStateUsing(fn ($record) => $record->pivot->seen_at)
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Ko\'rmagan'),

                TextColumn::make('responded_at_text')
                    ->label('Javob vaqti')
                    ->getStateUsing(fn ($record) => $record->pivot->responded_at)
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Javob bermagan'),
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Olib tashlash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}