<?php

namespace App\Filament\Resources\MyTasks\Tables;

use App\Filament\Resources\MyTasks\MyTaskResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MyTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.category.title')
                    ->label('Kategoriya')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('task.worker.user.name')
                    ->label('Ishchi')
                    ->sortable()
                    ->searchable()
                    ->alignCenter(),

                TextColumn::make('files_count')
                    ->label('Fayllar')
                    ->getStateUsing(fn ($record) => $record->files ? count($record->files) : 0)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray')
                    ->suffix(' ta')
                    ->alignCenter(),

                IconColumn::make('has_location')
                    ->label('GPS')
                    ->icon('heroicon-o-map-pin')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->getStateUsing(fn ($record) => $record->location !== null)
                    ->alignCenter(),

                IconColumn::make('status')
                    ->label('Tasdiqlangan')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Yuborilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('task_id')
                    ->label('Vazifa')
                    ->relationship('task.category', 'title')
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Holati')
                    ->options([
                        '1' => 'Tasdiqlangan',
                        '0' => 'Tasdiqlanmagan',
                    ])
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(2)
            ->recordActions([
                Action::make('download_files')
                    ->label('Fayllar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconButton()
                    ->color('info')
                    ->visible(fn ($record) => !empty($record->files))
                    ->modalHeading('Fayllarni yuklab olish')
                    ->modalContent(function ($record) {
                        $files = $record->files ?? [];
                        $html = '<div style="display: flex; flex-direction: column; gap: 8px;">';
                        foreach ($files as $file) {
                            $name = $file['name'] ?? basename($file['path'] ?? '');
                            $path = $file['path'] ?? '';
                            $size = isset($file['size']) ? round($file['size'] / 1024, 1) . ' KB' : '';
                            $url = Storage::disk('public')->url($path);
                            $html .= '<a href="' . $url . '" target="_blank" download style="display: flex; align-items: center; gap: 8px; padding: 10px 14px; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; color: #1f2937; transition: background 0.15s;">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; color: #3b82f6;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>';
                            $html .= '<div style="flex: 1;"><div style="font-weight: 500;">' . e($name) . '</div>';
                            if ($size) $html .= '<div style="font-size: 12px; color: #6b7280;">' . $size . '</div>';
                            $html .= '</div></a>';
                        }
                        $html .= '</div>';
                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Yopish'),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
