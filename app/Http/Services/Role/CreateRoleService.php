<?php

namespace App\Http\Services\Role;

use App\Repositories\RoleRepository;

class CreateRoleService
{

    public function create(array $data)
    {
        $repository = new RoleRepository();

        $permissionsIds = collect($data['permissions'])
            ->pluck('id')
            ->toArray();

        $role = $repository->create($data);

        $role->permissions()->sync($permissionsIds);

        return $role->load('permissions');
    }
}
