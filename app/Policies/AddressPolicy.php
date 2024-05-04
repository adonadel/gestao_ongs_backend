<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $permission = Permission::query()->where('name', 'address-view')->first();

        return $user->hasPermission($permission->name);
    }

    public function create(User $user)
    {
        $permission = Permission::query()->where('name', 'address-create')->first();

        return $user->hasPermission($permission->name);
    }

    public function update(User $user)
    {
        $permission = Permission::query()->where('name', 'address-update')->first();

        return $user->hasPermission($permission->name);
    }

    public function delete(User $user)
    {
        $permission = Permission::query()->where('name', 'address-delete')->first();

        return $user->hasPermission($permission->name);
    }
}
