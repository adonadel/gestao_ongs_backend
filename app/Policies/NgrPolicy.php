<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NgrPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $permission = Permission::query()->where('name', 'ngr-view')->first();

        return $user->hasPermission($permission->name);
    }

    public function create(User $user)
    {
        $permission = Permission::query()->where('name', 'ngr-create')->first();

        return $user->hasPermission($permission->name);
    }

    public function update(User $user)
    {
        $permission = Permission::query()->where('name', 'ngr-update')->first();

        return $user->hasPermission($permission->name);
    }

    public function delete(User $user)
    {
        $permission = Permission::query()->where('name', 'ngr-delete')->first();

        return $user->hasPermission($permission->name);
    }
}
