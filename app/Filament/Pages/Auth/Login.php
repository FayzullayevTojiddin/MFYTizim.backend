<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as FilamentLogin;

class Login extends FilamentLogin
{
    public function getHeading(): string
    {
        return 'Tizimga kirish';
    }

    public function getSubheading(): ?string
    {
        return 'Davom etish uchun ma\'lumotlaringizni kiriting';
    }

    protected function getEmailFormComponent(): \Filament\Forms\Components\TextInput
    {
        return parent::getEmailFormComponent()
            ->label('Email')
            ->prefixIcon('heroicon-o-envelope')
            ->placeholder('email@example.com');
    }

    protected function getPasswordFormComponent(): \Filament\Forms\Components\TextInput
    {
        return parent::getPasswordFormComponent()
            ->label('Parol')
            ->prefixIcon('heroicon-o-lock-closed')
            ->placeholder('••••••••');
    }

    protected function getRememberFormComponent(): \Filament\Forms\Components\Checkbox
    {
        return parent::getRememberFormComponent()
            ->label('Meni eslab qol');
    }

    protected function getAuthenticateFormAction(): \Filament\Actions\Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Kirish');
    }
}