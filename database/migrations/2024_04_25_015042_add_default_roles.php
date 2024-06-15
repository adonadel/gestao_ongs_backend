<?php

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $adminRole = Role::create([
            'name' => 'admin',
        ]);

        Role::create([
            'name' => 'user'
        ]);

        foreach (Permission::all() as $permission) {
            PermissionRole::create([
                'permission_id' => $permission->id,
                'role_id' => $adminRole->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $model = Role::whereName('admin')->first();

        if ($model) {
            foreach ($model->permissions() as $permission) {
                $model->permissions()->detach($permission->id);
            }

            $model->delete();
        }
    }
};
