<?php

namespace Database\Seeders;

use App\Models\People;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $person = People::factory()->create([
             'name' => 'Administrador',
             'email' => 'admin@teste.com',
        ]);

        $role = Role::factory()->create([
            'name' => 'admin'
        ]);

        $userCreatePermission = Permission::factory()->create([
            'name' => 'user-create'
        ]);

        $userUpdatePermission = Permission::factory()->create([
            'name' => 'user-update'
        ]);

        $userListPermission = Permission::factory()->create([
            'name' => 'user-list'
        ]);

        PermissionRole::factory()->create([
            'role_id' => $role->id,
            'permission_id' => $userCreatePermission->id,
        ]);

        PermissionRole::factory()->create([
            'role_id' => $role->id,
            'permission_id' => $userUpdatePermission->id,
        ]);

        PermissionRole::factory()->create([
            'role_id' => $role->id,
            'permission_id' => $userListPermission->id,
        ]);

        User::factory()->create([
            'password' => Hash::make('123456'),
            'people_id' => $person->id,
            'role_id' => $role->id
        ]);
    }
}
