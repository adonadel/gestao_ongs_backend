<?php

namespace App\Http\Services\Permission;

use App\Repositories\PermissionRepository;

class QueryPermissionService
{
    public function getPermissions(array $filters)
    {
        return (new PermissionRepository())->getPermissions($filters);
    }

}
