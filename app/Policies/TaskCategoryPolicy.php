<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\TaskCategory;

class TaskCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function view(User $user, TaskCategory $category): bool
    {
        return $user->role !== UserRole::ISHCHI;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function update(User $user, TaskCategory $category): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }

    public function delete(User $user, TaskCategory $category): bool
    {
        return in_array($user->role, [UserRole::SUPER, UserRole::YORDAMCHI]);
    }
}