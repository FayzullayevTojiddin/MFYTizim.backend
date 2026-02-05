<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Worker;

class WorkerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function view(User $user, Worker $worker): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function update(User $user, Worker $worker): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function delete(User $user, Worker $worker): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }
}