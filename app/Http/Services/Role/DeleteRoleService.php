<?php

namespace App\Http\Services\Role;

use App\Exceptions\RoleAlreadyInUseException;
use App\Repositories\PermissionRoleRepository;
use App\Repositories\RoleRepository;

class DeleteRoleService
{

    public function delete(int $id)
    {
        $repository = new RoleRepository();

        $permissionRoleRepository = new PermissionRoleRepository();

        $checkIfAreInUse = $permissionRoleRepository->newQuery()
            ->where('role_id', $id)
            ->exists();

        if ($checkIfAreInUse) {
            throw new RoleAlreadyInUseException('Nível de permissão já em uso');
        }

        $role = $repository->getById($id);

        $role->permissions()->detach();

        return $repository->delete($role);
    }
}
