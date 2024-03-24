<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class UserPolicy
{
    public function view(User $user)
    {
        $permission = Permission::query()->where('name', 'user-view')->first();

        return $user->hasPermission($permission->name);
    }

    public function create(User $user)
    {
        $permission = Permission::query()->where('name', 'user-create')->first();

        return $user->hasPermission($permission->name);
    }

    public function update(User $user)
    {
        $permission = Permission::query()->where('name', 'user-update')->first();

        return $user->hasPermission($permission->name);
    }
}
