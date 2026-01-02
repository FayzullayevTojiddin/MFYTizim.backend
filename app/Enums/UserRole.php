<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER = 'super';
    case HOKIM = 'hokim';
    case YORDAMCHI = 'yordamchi';
    case ISHCHI = 'ishchi';

    public function label(): string
    {
        return match ($this) {
            self::SUPER => 'Super',
            self::HOKIM => 'Hokim',
            self::YORDAMCHI => 'Yordamchi',
            self::ISHCHI => 'Ishchi',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($role) => [$role->value => $role->label()])
            ->toArray();
    }
}