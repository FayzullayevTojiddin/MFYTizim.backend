<?php

namespace App\Filament\Resources\Meets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;

class MeetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Yig‘ilish nomi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_at')
                    ->label('Sana')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dan'),
                        DatePicker::make('until')
                            ->label('Gacha'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'],
                                fn ($q) => $q->whereDate('date_at', '>=', $data['from']))
                            ->when($data['until'],
                                fn ($q) => $q->whereDate('date_at', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                EditAction::make()->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}