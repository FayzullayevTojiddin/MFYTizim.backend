<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Foydalanuvchi ma'lumotlari")
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Rasm')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->maxSize(1024)
                            ->imagePreviewHeight('100')
                            ->nullable(),

                        TextInput::make('name')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make([
                    TextInput::make('email')
                        ->required()
                        ->email(),
                    
                    TextInput::make('password')
                        ->label('Parol')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->afterStateHydrated(fn ($component) => $component->state('')),
                    
                    Select::make('role')
                        ->options(function () {
                            $options = UserRole::options();

                            if (auth()->user()?->role !== UserRole::SUPER->value) {
                                unset($options[UserRole::SUPER->value]);
                            }

                            return $options;
                        })
                        ->required(),
                ])
                ->columns(3)
                ->columnSpanFull(),
            ]);
    }
}
