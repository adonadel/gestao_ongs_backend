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
                'type' => 'user',
            ],
            [
                'name' => 'user-update',
                'display_name' => 'Atualizar usuários',
                'type' => 'user',
            ],
            [
                'name' => 'user-create',
                'display_name' => 'Criar usuários',
                'type' => 'user',
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'Excluir usuários',
                'type' => 'user',
            ],
            [
                'name' => 'animal-view',
                'display_name' => 'Visualizar Animais',
                'type' => 'animal',
            ],
            [
                'name' => 'animal-update',
                'display_name' => 'Atualizar Animais',
                'type' => 'animal',
            ],
            [
                'name' => 'animal-create',
                'display_name' => 'Criar Animais',
                'type' => 'animal',
            ],
            [
                'name' => 'animal-delete',
                'display_name' => 'Excluir Animais',
                'type' => 'animal',
            ],
            [
                'name' => 'adoption-view',
                'display_name' => 'Visualizar Adoções',
                'type' => 'adoption',
            ],
            [
                'name' => 'adoption-update',
                'display_name' => 'Atualizar Adoções',
                'type' => 'adoption',
            ],
            [
                'name' => 'adoption-create',
                'display_name' => 'Criar Adoções',
                'type' => 'adoption',
            ],
            [
                'name' => 'adoption-delete',
                'display_name' => 'Excluir Adoções',
                'type' => 'adoption',
            ],
            [
                'name' => 'adoption-update-status',
                'display_name' => 'Atualizar status das Adoções',
                'type' => 'adoption',
            ],
            [
                'name' => 'address-view',
                'display_name' => 'Visualizar Endereços',
                'type' => 'address',
            ],
            [
                'name' => 'address-update',
                'display_name' => 'Atualizar Endereços',
                'type' => 'address',
            ],
            [
                'name' => 'address-create',
                'display_name' => 'Criar Endereços',
                'type' => 'address',
            ],
            [
                'name' => 'address-delete',
                'display_name' => 'Excluir Endereços',
                'type' => 'address',
            ],
            [
                'name' => 'event-view',
                'display_name' => 'Visualizar Eventos',
                'type' => 'event',
            ],
            [
                'name' => 'event-update',
                'display_name' => 'Atualizar Eventos',
                'type' => 'event',
            ],
            [
                'name' => 'event-create',
                'display_name' => 'Criar Eventos',
                'type' => 'event',
            ],
            [
                'name' => 'event-delete',
                'display_name' => 'Excluir Eventos',
                'type' => 'event',
            ],
            [
                'name' => 'finance-view',
                'display_name' => 'Visualizar Finanças',
                'type' => 'finance',
            ],
            [
                'name' => 'finance-update',
                'display_name' => 'Atualizar Finanças',
                'type' => 'finance',
            ],
            [
                'name' => 'finance-create',
                'display_name' => 'Criar Finanças',
                'type' => 'finance',
            ],
            [
                'name' => 'finance-delete',
                'display_name' => 'Excluir Finanças',
                'type' => 'finance',
            ],
            [
                'name' => 'media-view',
                'display_name' => 'Visualizar Imagens',
                'type' => 'media',
            ],
            [
                'name' => 'media-update',
                'display_name' => 'Atualizar Imagens',
                'type' => 'media',
            ],
            [
                'name' => 'media-create',
                'display_name' => 'Criar Imagens',
                'type' => 'media',
            ],
            [
                'name' => 'media-delete',
                'display_name' => 'Excluir Imagens',
                'type' => 'media',
            ],
            [
                'name' => 'ngr-view',
                'display_name' => 'Visualizar Dados da ong',
                'type' => 'ngr',
            ],
            [
                'name' => 'ngr-update',
                'display_name' => 'Atualizar Dados da ong',
                'type' => 'ngr',
            ],
            [
                'name' => 'ngr-create',
                'display_name' => 'Criar Dados da ong',
                'type' => 'ngr',
            ],
            [
                'name' => 'ngr-delete',
                'display_name' => 'Excluir Dados da ong',
                'type' => 'ngr',
            ],
            [
                'name' => 'permission-view',
                'display_name' => 'Visualizar Permissões',
                'type' => 'permission',
            ],
            [
                'name' => 'permission-update',
                'display_name' => 'Atualizar Permissões',
                'type' => 'permission',
            ],
            [
                'name' => 'permission-create',
                'display_name' => 'Criar Permissões',
                'type' => 'permission',
            ],
            [
                'name' => 'permission-delete',
                'display_name' => 'Excluir Permissões',
                'type' => 'permission',
            ],
            [
                'name' => 'role-view',
                'display_name' => 'Visualizar Níveis de permissão',
                'type' => 'role',
            ],
            [
                'name' => 'role-update',
                'display_name' => 'Atualizar Níveis de permissão',
                'type' => 'role',
            ],
            [
                'name' => 'role-create',
                'display_name' => 'Criar Níveis de permissão',
                'type' => 'role',
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Excluir Níveis de permissão',
                'type' => 'role',
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
