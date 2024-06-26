<?php

use App\Enums\UserTypeEnum;
use App\Models\People;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $person = People::create([
            'name' => 'Administrador',
            'email' => 'admin@teste.com',
            'cpf_cnpj' => '123.456.789-10',
        ]);

        $role = Role::whereName('admin')->first();

        User::create([
            'password' => Hash::make('Pw12345678!'),
            'people_id' => $person->id,
            'role_id' => $role->id,
            'type' => UserTypeEnum::INTERNAL,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $model = People::whereEmail('admin@teste.com')->first();

        if ($model) {
            foreach ($model->user->permissions() as $permission) {
                $model->user->permissions()->detach($permission->id);
            }

            $model->user->delete();
            $model->delete();
        }
    }
};
