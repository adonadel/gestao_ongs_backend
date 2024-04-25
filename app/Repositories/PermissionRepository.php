<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends Repository
{

    protected function getModelClass()
    {
        return Permission::class;
    }
}
