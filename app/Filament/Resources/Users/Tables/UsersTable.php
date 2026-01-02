<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\UserRole;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("ID"),
                TextColumn::make('name')    
                    ->label("Foydalanuvchining Nomi"),
                BadgeColumn::make('role')
                    ->label("Darajasi")
                    ->formatStateUsing(fn ($state) => UserRole::from($state)->label())
                    ->color(fn ($state) => match ($state) {
                        UserRole::SUPER->value => 'danger',
                        UserRole::HOKIM->value => 'primary',
                        UserRole::YORDAMCHI->value => 'warning',
                        UserRole::ISHCHI->value => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('email'),
                TextColumn::make('created_at')
                    ->label("Yaratilingan Vaqti"),
            ])
            ->searchable(['id', 'email', 'name'])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->button(),
                    DeleteAction::make()->button()
                ]),
            ])
            ->toolbarActions([
                //  
            ]);
    }
}
