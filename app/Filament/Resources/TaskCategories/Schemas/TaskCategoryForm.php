<?php

namespace App\Filament\Resources\TaskCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class TaskCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->unique()
                    ->label("Sarlavha")
                    ->required(),
                RichEditor::make('description')
                    ->nullable()
            ])
            ->columns(1);
    }
}
