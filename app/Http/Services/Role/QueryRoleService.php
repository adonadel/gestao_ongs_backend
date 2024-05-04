<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class QueryRoleService
{
    public function getRoles(array $filters)
    {
        return (new RoleRepository())->getRoles($filters);
    }

    public function getRoleById(int $id)
    {
        return (new RoleRepository())->getById($id);
    }
}
