<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Nrg;
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

        $address = Address::factory()->create([
            'street' => 'Avenida Centenário',
            'neighborhood' => 'Centro',
            'city' => 'Criciúma',
            'state' => 'SC',
            'number' => '1000',
        ]);

        Nrg::factory()->create([
            'name' => 'Patinhas Carentes',
            'cnpj' => '84.058.017/0001-90',
            'description' => 'Ong que sonha em fazer a diferença na vida dos animais necessitados na cidade de Criciúma',
            'address_id' => $address->id
        ]);
    }
}
