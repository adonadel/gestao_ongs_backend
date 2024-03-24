<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionRoleFactory extends Factory
{

    public function definition(): array
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();

        return [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ];
    }
}
