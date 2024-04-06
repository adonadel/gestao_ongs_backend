<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\Permission;

class MediaPolicy
{
    public function view(Media $media)
    {
        $permission = Permission::query()->where('name', 'media-view')->first();

        return $media->hasPermission($permission->name);
    }

    public function create(Media $media)
    {
        $permission = Permission::query()->where('name', 'media-create')->first();

        return $media->hasPermission($permission->name);
    }

    public function update(Media $media)
    {
        $permission = Permission::query()->where('name', 'media-update')->first();

        return $media->hasPermission($permission->name);
    }
}
