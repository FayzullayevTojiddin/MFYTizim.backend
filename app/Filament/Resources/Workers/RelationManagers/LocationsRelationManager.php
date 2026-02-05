<?php

namespace App\Filament\Resources\Workers\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    protected static ?string $title = 'Joylashuvlar';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('address')
                    ->label('Manzil')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->address)
                    ->placeholder('Aniqlanmagan'),

                TextColumn::make('accuracy')
                    ->label('Aniqlik')
                    ->formatStateUsing(fn ($state) => $state ? round($state) . ' m' : '-')
                    ->sortable(),

                TextColumn::make('battery_level')
                    ->label('Batareya')
                    ->formatStateUsing(fn ($state) => $state ? round($state) . '%' : '-')
                    ->color(fn ($state) => match (true) {
                        $state >= 50 => 'success',
                        $state >= 20 => 'warning',
                        $state > 0 => 'danger',
                        default => 'gray',
                    })
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Vaqt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('today')
                    ->label('Vaqt')
                    ->options([
                        'today' => 'Bugun',
                        'week' => 'Bu hafta',
                        'month' => 'Bu oy',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'today' => $query->whereDate('created_at', today()),
                            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                            'month' => $query->whereMonth('created_at', now()->month),
                            default => $query,
                        };
                    })
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
                    ->url(fn ($record) => 'https://www.google.com/maps?q=' . $record->latitude . ',' . $record->longitude)
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }
}