<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->permissions() as $permission) {
            Permission::create($permission);
        }
    }

    private function permissions()
    {
        return [
            [
                'name' => 'user-view',
                'display_name' => 'Visualizar usuários',
            ],
            [
                'name' => 'user-update',
                'display_name' => 'Atualizar usuários',
            ],
            [
                'name' => 'user-create',
                'display_name' => 'Criar usuários',
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'Excluir usuários',
            ],
            [
                'name' => 'animal-view',
                'display_name' => 'Visualizar Animais',
            ],
            [
                'name' => 'animal-update',
                'display_name' => 'Atualizar Animais',
            ],
            [
                'name' => 'animal-create',
                'display_name' => 'Criar Animais',
            ],
            [
                'name' => 'animal-delete',
                'display_name' => 'Excluir Animais',
            ],
            [
                'name' => 'adoption-view',
                'display_name' => 'Visualizar Adoções',
            ],
            [
                'name' => 'adoption-update',
                'display_name' => 'Atualizar Adoções',
            ],
            [
                'name' => 'adoption-create',
                'display_name' => 'Criar Adoções',
            ],
            [
                'name' => 'adoption-delete',
                'display_name' => 'Excluir Adoções',
            ],
            [
                'name' => 'adoption-update-status',
                'display_name' => 'Atualizar status das Adoções',
            ],
            [
                'name' => 'address-view',
                'display_name' => 'Visualizar Endereços',
            ],
            [
                'name' => 'address-update',
                'display_name' => 'Atualizar Endereços',
            ],
            [
                'name' => 'address-create',
                'display_name' => 'Criar Endereços',
            ],
            [
                'name' => 'address-delete',
                'display_name' => 'Excluir Endereços',
            ],
            [
                'name' => 'event-view',
                'display_name' => 'Visualizar Eventos',
            ],
            [
                'name' => 'event-update',
                'display_name' => 'Atualizar Eventos',
            ],
            [
                'name' => 'event-create',
                'display_name' => 'Criar Eventos',
            ],
            [
                'name' => 'event-delete',
                'display_name' => 'Excluir Eventos',
            ],
            [
                'name' => 'finance-view',
                'display_name' => 'Visualizar Finanças',
            ],
            [
                'name' => 'finance-update',
                'display_name' => 'Atualizar Finanças',
            ],
            [
                'name' => 'finance-create',
                'display_name' => 'Criar Finanças',
            ],
            [
                'name' => 'finance-delete',
                'display_name' => 'Excluir Finanças',
            ],
            [
                'name' => 'media-view',
                'display_name' => 'Visualizar Imagens',
            ],
            [
                'name' => 'media-update',
                'display_name' => 'Atualizar Imagens',
            ],
            [
                'name' => 'media-create',
                'display_name' => 'Criar Imagens',
            ],
            [
                'name' => 'media-delete',
                'display_name' => 'Excluir Imagens',
            ],
            [
                'name' => 'ngr-view',
                'display_name' => 'Visualizar Dados da ong',
            ],
            [
                'name' => 'ngr-update',
                'display_name' => 'Atualizar Dados da ong',
            ],
            [
                'name' => 'ngr-create',
                'display_name' => 'Criar Dados da ong',
            ],
            [
                'name' => 'ngr-delete',
                'display_name' => 'Excluir Dados da ong',
            ],
            [
                'name' => 'permission-view',
                'display_name' => 'Visualizar Permissões',
            ],
            [
                'name' => 'permission-update',
                'display_name' => 'Atualizar Permissões',
            ],
            [
                'name' => 'permission-create',
                'display_name' => 'Criar Permissões',
            ],
            [
                'name' => 'permission-delete',
                'display_name' => 'Excluir Permissões',
            ],
            [
                'name' => 'role-view',
                'display_name' => 'Visualizar Níveis de permissão',
            ],
            [
                'name' => 'role-update',
                'display_name' => 'Atualizar Níveis de permissão',
            ],
            [
                'name' => 'role-create',
                'display_name' => 'Criar Níveis de permissão',
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Excluir Níveis de permissão',
            ],
        ];
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->permissions() as $permission) {
            $model = Permission::whereName($permission['name'])->first();

            if ($model) {
                $model->delete();
            }
        }
    }
};
