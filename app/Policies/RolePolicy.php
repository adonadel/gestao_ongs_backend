<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $permission = Permission::query()->where('name', 'role-view')->first();

        return $user->hasPermission($permission->name);
    }

    public function create(User $user)
    {
        $permission = Permission::query()->where('name', 'role-create')->first();

        return $user->hasPermission($permission->name);
    }

    public function update(User $user)
    {
        $permission = Permission::query()->where('name', 'role-update')->first();

        return $user->hasPermission($permission->name);
    }

    public function delete(User $user)
    {
        $permission = Permission::query()->where('name', 'role-delete')->first();

        return $user->hasPermission($permission->name);
    }
}
