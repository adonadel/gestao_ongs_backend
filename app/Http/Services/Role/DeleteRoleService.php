<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class DeleteRoleService
{

    public function delete(int $id)
    {
        $repository = new RoleRepository();

        $role = $repository->getById($id);

        return $repository->delete($role);
    }
}
