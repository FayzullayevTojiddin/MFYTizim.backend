<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make('Profil rasmi')
                    ->description('Foydalanuvchi avatari')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Rasm')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('users')
                            ->maxSize(2048)
                            ->visibility('public')
                            ->alignCenter(),
                    ])
                    ->columnSpan(1),

                Section::make('Asosiy ma\'lumotlar')
                    ->description('Foydalanuvchi haqida asosiy ma\'lumotlar')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ism')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-user'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-envelope'),

                        TextInput::make('password')
                            ->label('Parol')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->minLength(8)
                            ->prefixIcon('heroicon-o-lock-closed'),

                        Select::make('role')
                            ->label('Rol')
                            ->enum(UserRole::class)
                            ->options(UserRole::class)
                            ->default(UserRole::ISHCHI)
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-shield-check'),
                    ])
                    ->columns(2)
                    ->columnSpan(3),
            ]);
    }
}