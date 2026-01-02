<?php

namespace App\Filament\Resources\Neighboroods\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class NeighboroodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mahalla maʼlumotlari')
                    ->schema([
                        TextInput::make('title')
                            ->label('Mahalla nomi')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Mahalla yordamchisi')
                    ->schema([
                        Select::make('user_id')
                            ->label('Hokim yordamchisi')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) =>
                                    $query->where('role', UserRole::ISHCHI->value)
                            )
                            ->searchable(['name', 'email'])
                            ->required(),
                    ])
            ])
            ->columns(2);
    }
}