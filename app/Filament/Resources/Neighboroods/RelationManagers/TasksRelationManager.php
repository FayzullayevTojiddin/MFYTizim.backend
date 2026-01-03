<?php

namespace App\Filament\Resources\Neighboroods\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TaskCategory;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('task_category_id')
                ->label('Kategoriya')
                ->options(TaskCategory::pluck('title', 'id'))
                ->searchable()
                ->required(),

            Textarea::make('description')
                ->label('Izoh')
                ->required()
                ->columnSpanFull(),

            Select::make('status')
                ->label('Holati')
                ->options([
                    'new' => 'Yangi',
                    'apply' => 'Qabul qilingan',
                    'cancelled' => 'Bekor qilingan',
                ])
                ->required(),

            TextInput::make('latitude')
                ->numeric()
                ->label('Latitude'),

            TextInput::make('longitude')
                ->numeric()
                ->label('Longitude'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('category.title')
                    ->label('Kategoriya')
                    ->sortable()
                    ->searchable(),

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
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()->button(),
                DeleteAction::make()->button(),
            ]);
    }
}
