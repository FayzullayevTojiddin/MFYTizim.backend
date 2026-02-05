<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Meet;

class MeetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function view(User $user, Meet $meet): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function update(User $user, Meet $meet): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function delete(User $user, Meet $meet): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }
}