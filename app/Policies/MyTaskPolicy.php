<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\MyTask;

class MyTaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function view(User $user, MyTask $myTask): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function update(User $user, MyTask $myTask): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function delete(User $user, MyTask $myTask): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }
}