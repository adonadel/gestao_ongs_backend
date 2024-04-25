<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class UpdateRoleService
{

    public function update(array $data, int $id)
    {
        $repository = new RoleRepository();

        $role = $repository->getById($id);

        $repository->update($role, $data);

        return $role;
    }
}
