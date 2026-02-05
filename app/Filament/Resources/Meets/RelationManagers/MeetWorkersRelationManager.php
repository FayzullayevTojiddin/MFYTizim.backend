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
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'declined' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'accepted' => 'Qabul qildi',
                        'declined' => 'Rad etdi',
                    }),

                IconColumn::make('pivot.seen_at')
                    ->label('Ko\'rganmi')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->pivot->seen_at !== null)
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('pivot.seen_at')
                    ->label('Ko\'rgan vaqti')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Ko\'rmagan'),

                TextColumn::make('pivot.responded_at')
                    ->label('Javob vaqti')
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