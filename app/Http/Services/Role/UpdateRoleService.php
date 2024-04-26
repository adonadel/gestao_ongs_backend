<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class UpdateRoleService
{

    public function update(array $data, int $id)
    {
        $repository = new RoleRepository();

        $permissionsIds = collect($data['permissions'])
            ->pluck('id')
            ->toArray();

        $role = $repository->getById($id);

        $role->permissions()->detach();

        $repository->update($role, $data);

        $role->permissions()->sync($permissionsIds);

        return $role->load('permissions');
    }
}
