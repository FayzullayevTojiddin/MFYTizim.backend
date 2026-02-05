<?php

namespace App\Filament\Resources\Workers\RelationManagers;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Vazifalar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('task_category_id')
                    ->label('Kategoriya')
                    ->relationship('category', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->prefixIcon('heroicon-o-tag'),

                TextInput::make('count')
                    ->label('Soni')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->prefixIcon('heroicon-o-hashtag'),

                DateTimePicker::make('deadline_at')
                    ->label('Muddat')
                    ->required()
                    ->native(false)
                    ->prefixIcon('heroicon-o-calendar'),

                DateTimePicker::make('completed_at')
                    ->label('Bajarilgan vaqt')
                    ->native(false)
                    ->prefixIcon('heroicon-o-check'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.title')
                    ->label('Kategoriya')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('count')
                    ->label('Soni')
                    ->sortable(),

                TextColumn::make('deadline_at')
                    ->label('Muddat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : 'default'),

                TextColumn::make('completed_at')
                    ->label('Bajarilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('Hali bajarilmagan'),

                IconColumn::make('status')
                    ->label('Holati')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                SelectFilter::make('task_category_id')
                    ->label('Kategoriya')
                    ->relationship('category', 'title')
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
            ->filtersFormColumns(2)
            ->headerActions([
                CreateAction::make()
                    ->label('Vazifa qo\'shish')
                    ->icon('heroicon-o-plus'),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
                Action::make('all_tasks')
                    ->iconButton()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn () => TaskResource::getUrl())
                    ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('deadline_at', 'asc');
    }
}