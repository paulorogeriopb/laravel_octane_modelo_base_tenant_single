<?php

declare(strict_types=1);

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Capturar possíveis exceções durante a execução do seeder.
        try {
            // Super Admin – sem permissão manual, pois tem acesso total via Gate::before
            Role::firstOrCreate(['name' => 'Super Admin']);

            // Admin – com permissões explícitas
            $admin = Role::firstOrCreate(['name' => 'Admin']);

            $admin->givePermissionTo([
                'dashboard',
                'profile-edit',
                'profile-destroy',
                'profile-show',
                'profile-password-edit',

                'audit-index',

                'translation-index',
                'translation-create',
                'translation-show',
                'translation-edit',
                'translation-destroy',

                'permission-index',
                'permission-create',
                'permission-show',
                'permission-edit',
                'permission-destroy',

                'permission-role-index',

                'role-index',
                'role-create',
                'role-destroy',
                'role-show',
                'role-edit',

                'user-index',
                'user-create',
                'user-show',
                'user-edit',
                'user-destroy',
                'user-edit-password',
                'user-role-edit',

                'user-status-index',
                'user-status-create',
                'user-status-show',
                'user-status-edit',
                'user-status-destroy',

                //Paginas do sistemas

                //Cursos
                'cursos-index',
                'cursos-create',
                'cursos-show',
                'cursos-edit',
                'cursos-destroy',
            ]);

            // User – com permissões limitadas
            $user = Role::firstOrCreate(['name' => 'User']);

            $user->givePermissionTo([
                'dashboard',
                'profile-edit',
                'profile-destroy',
                'profile-show',
                'profile-password-edit',

                'cursos-index',
                'cursos-show',
            ]);
        } catch (Exception $e) {
            // Salvar log
            Log::notice('Papel não cadastrado.', ['error' => $e->getMessage()]);
        }
    }
}