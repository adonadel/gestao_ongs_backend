<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class CreateRoleService
{

    public function create(array $data)
    {
        $repository = new RoleRepository();

        $role = $repository->create($data);

        return $role;
    }
}
