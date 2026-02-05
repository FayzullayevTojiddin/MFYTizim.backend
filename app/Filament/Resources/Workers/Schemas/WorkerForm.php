<?php

namespace App\Filament\Resources\Workers\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ishchi ma\'lumotlari')
                    ->description('Ishchi haqida asosiy ma\'lumotlar')
                    ->schema([
                        Select::make('user_id')
                            ->label('Foydalanuvchi')
                            ->relationship('user', 'name', fn ($query) => $query->where('role', UserRole::ISHCHI))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Ism')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email'),
                                TextInput::make('password')
                                    ->columnSpan(2)
                                    ->label('Parol')
                                    ->password()
                                    ->required(),
                            ]),

                        TextInput::make('title')
                            ->label('Lavozim')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-briefcase'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}