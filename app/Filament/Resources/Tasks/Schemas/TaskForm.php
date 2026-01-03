<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;


class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('task_category_id')
                ->label('Vazifa kategoriyasi')
                ->relationship('category', 'title')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('neighborood_id')
                ->label('Mahalla')
                ->relationship('neighborood', 'title')
                ->searchable()
                ->preload()
                ->required(),

            Textarea::make('description')
                ->label('Vazifa tavsifi')
                ->rows(6)
                ->columnSpanFull()
                ->nullable(),

            Placeholder::make('map_link')
                ->label('Lokatsiya')
                ->content(function ($record) {
                    if (! $record?->latitude || ! $record?->longitude) {
                        return 'Lokatsiya belgilanmagan';
                    }

                    $url = "https://www.google.com/maps?q={$record->latitude},{$record->longitude}&t=k";

                    return new HtmlString("
                        <a href='{$url}'
                        target='_blank'
                        style='
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                width: 100%;
                                padding: 10px 16px;
                                font-size: 14px;
                                font-weight: 600;
                                color: #ffffff;
                                background-color: #16a34a;
                                border-radius: 8px;
                                text-decoration: none;
                                transition: background-color 0.2s ease;
                        '
                        onmouseover=\"this.style.backgroundColor='#15803d'\"
                        onmouseout=\"this.style.backgroundColor='#16a34a'\"
                        >
                            🌍 Sputnikdan ko‘rish
                        </a>
                    ");

                }),

            FileUpload::make('file')
                ->label('Fayl')
                ->directory('tasks/files')
                ->downloadable()
                ->openable()
                ->preserveFilenames()
                ->nullable(),

            Grid::make(2)->schema([
                Placeholder::make('created_at')
                    ->label('Yaratilgan vaqti')
                    ->content(fn ($record) => $record?->created_at?->format('d.m.Y H:i')),

                Placeholder::make('updated_at')
                    ->label('So‘nggi yangilangan')
                    ->content(fn ($record) => $record?->updated_at?->format('d.m.Y H:i')),
            ]),
        ]);
    }
}