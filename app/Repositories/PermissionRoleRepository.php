<?php

namespace App\Repositories;

use App\Models\PermissionRole;

class PermissionRoleRepository extends Repository
{
    protected function getModelClass()
    {
        return PermissionRole::class;
    }
}
