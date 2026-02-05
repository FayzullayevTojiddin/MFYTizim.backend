<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function view(User $user, User $model): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SUPER;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === UserRole::SUPER;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === UserRole::SUPER && $user->id !== $model->id;
    }
}