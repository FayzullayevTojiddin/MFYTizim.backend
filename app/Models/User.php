<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role !== UserRole::ISHCHI->value;
    }

    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'image'
    ];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
