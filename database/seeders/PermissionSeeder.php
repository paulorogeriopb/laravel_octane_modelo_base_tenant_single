<?php

declare(strict_types=1);

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Capturar possíveis exceções durante a execução do seeder.
        try {
            // Criar o array de páginas
            $permissions = [
                ['title'=> 'Dashboard', 'name' => 'dashboard'],

                ['title'=> 'Visualizar o perfil', 'name' => 'profile-show'],
                ['title'=> 'Editar o perfil', 'name' => 'profile-edit'],
                ['title'=> 'Editar a senha do perfil', 'name' => 'profile-password-edit'],
                ['title'=> 'Apagar o perfil', 'name' => 'profile-destroy'],

                ['title'=> 'Auditoria', 'name' => 'audit-index'],

                ['title'=> 'Listar as traduções', 'name' => 'translation-index'],
                ['title'=> 'Visualizar a tradução', 'name' => 'translation-show'],
                ['title'=> 'Cadastrar a tradução', 'name' => 'translation-create'],
                ['title'=> 'Editar a tradução', 'name' => 'translation-edit'],
                ['title'=> 'Apagar a tradução', 'name' => 'translation-destroy'],

                ['title'=> 'Listar os papéis', 'name' => 'role-index'],
                ['title'=> 'Visualizar o papel', 'name' => 'role-show'],
                ['title'=> 'Cadastrar o papel', 'name' => 'role-create'],
                ['title'=> 'Editar o papel', 'name' => 'role-edit'],
                ['title'=> 'Apagar o papel', 'name' => 'role-destroy'],

                ['title'=> 'Listar as permissões do papel', 'name' => 'permission-role-index'],

                ['title'=> 'Listar as permissões', 'name' => 'permission-index'],
                ['title'=> 'Visualizar a permissão', 'name' => 'permission-show'],
                ['title'=> 'Cadastrar a permissão', 'name' => 'permission-create'],
                ['title'=> 'Editar a permissão', 'name' => 'permission-edit'],
                ['title'=> 'Apagar a permissão', 'name' => 'permission-destroy'],

                ['title'=> 'Listar os usuários', 'name' => 'user-index'],
                ['title'=> 'Visualizar o usuário', 'name' => 'user-show'],
                ['title'=> 'Cadastrar o usuário', 'name' => 'user-create'],
                ['title'=> 'Editar o usuário', 'name' => 'user-edit'],
                ['title'=> 'Editar a senha do usuário', 'name' => 'user-edit-password'],
                ['title'=> 'Apagar o usuário', 'name' => 'user-destroy'],
                ['title'=> 'Editar papéis do usuário', 'name' => 'user-role-edit'],

                ['title'=> 'Listar os status usuários', 'name' => 'user-status-index'],
                ['title'=> 'Visualizar o status usuário', 'name' => 'user-status-show'],
                ['title'=> 'Cadastrar o status usuário', 'name' => 'user-status-create'],
                ['title'=> 'Editar o status usuário', 'name' => 'user-status-edit'],
                ['title'=> 'Apagar o status usuário', 'name' => 'user-status-destroy'],

                //Paginas do sistemas

                //Cursos
                ['title'=> 'Listar os cursos', 'name' => 'cursos-index'],
                ['title'=> 'Visualizar o curso', 'name' => 'cursos-show'],
                ['title'=> 'Cadastrar o curso', 'name' => 'cursos-create'],
                ['title'=> 'Editar o curso', 'name' => 'cursos-edit'],
                ['title'=> 'Apagar o curso', 'name' => 'cursos-destroy'],
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission['name']],
                    [
                        'title' => $permission['title'],
                        'guard_name' => 'web',
                    ],
                );
            }
        } catch (Exception $e) {
            // Salvar log
            Log::notice('Permissão não cadastrada.', ['error' => $e->getMessage()]);
        }
    }
}
