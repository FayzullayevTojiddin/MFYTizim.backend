<?php

namespace App\Filament\Resources\MyTasks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class MyTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Ish ma\'lumotlari')
                    ->description('Bajarilgan ish haqida ma\'lumot')
                    ->schema([
                        Select::make('task_id')
                            ->label('Vazifa')
                            ->relationship('task', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->category->title . ' — ' . $record->worker->title)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-clipboard-document'),

                        Textarea::make('description')
                            ->label('Tavsif')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Bajarilgan ish haqida batafsil...'),
                    ])
                    ->columnSpan(1),

                Section::make('Fayllar va joylashuv')
                    ->description('Isbotlovchi fayllar va lokatsiya')
                    ->schema([
                        FileUpload::make('files')
                            ->label('Fayllar')
                            ->multiple()
                            ->directory('my-tasks')
                            ->disk('public')
                            ->maxSize(5120)
                            ->maxFiles(5)
                            ->reorderable()
                            ->acceptedFileTypes(['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->afterStateHydrated(function ($component, $state) {
                                if (!$state) return;
                                
                                $paths = collect($state)->map(function ($file) {
                                    return is_array($file) ? $file['path'] : $file;
                                })->filter()->toArray();
                                
                                $component->state($paths);
                            })
                            ->dehydrateStateUsing(function ($state) {
                                if (!$state) return [];
                                
                                return collect($state)->map(function ($path) {
                                    if (is_array($path)) return $path;
                                    
                                    $fullPath = Storage::disk('public')->path($path);
                                    
                                    return [
                                        'name' => basename($path),
                                        'path' => $path,
                                        'url' => '/storage/' . $path,
                                        'size' => file_exists($fullPath) ? filesize($fullPath) : 0,
                                        'mime' => file_exists($fullPath) ? mime_content_type($fullPath) : 'application/octet-stream',
                                    ];
                                })->toArray();
                            }),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('location.lat')
                                    ->label('Kenglik (lat)')
                                    ->numeric()
                                    ->placeholder('41.2995'),

                                TextInput::make('location.lng')
                                    ->label('Uzunlik (lng)')
                                    ->numeric()
                                    ->placeholder('69.2401'),
                            ]),
                    ])
                    ->columnSpan(1),
            ]);
    }
}