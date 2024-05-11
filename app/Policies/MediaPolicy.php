<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $permission = Permission::query()->where('name', 'media-view')->first();

        return $user->hasPermission($permission->name);
    }

    public function create(User $user)
    {
        $permission = Permission::query()->where('name', 'media-create')->first();

        return $user->hasPermission($permission->name);
    }

    public function update(User $user)
    {
        $permission = Permission::query()->where('name', 'media-update')->first();

        return $user->hasPermission($permission->name);
    }

    public function delete(User $user)
    {
        $permission = Permission::query()->where('name', 'media-delete')->first();

        return $user->hasPermission($permission->name);
    }
}
