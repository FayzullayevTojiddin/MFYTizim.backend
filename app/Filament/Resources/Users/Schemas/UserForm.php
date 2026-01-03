<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
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
            ]);
    }
}
