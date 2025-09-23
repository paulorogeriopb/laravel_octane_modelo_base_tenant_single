<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(Role $role)
    {
        if ($role->name === 'Super Admin') {
            Log::info('A permissão do super admin não pode ser acessada.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

            return redirect()->route('roles.index')->with('error', 'A permissão do super admin não pode ser acessada!');
        }

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->all();

        $permissions = Permission::orderBy('name')->paginate(15);
        $users = User::orderBy('name')->get(); // Todos os usuários (pode filtrar se quiser)

        return view('role_permissions.index', ['role' => $role, 'rolePermissions' => $rolePermissions, 'permissions' => $permissions, 'users' => $users]);
    }

    public function toggleUser(Role $role, User $user)
    {
        try {
            $action = $user->hasRole($role) ? 'removeRole' : 'assignRole';
            $user->$action($role);

            Log::info('Usuário atualizado no papel.', [
                'role_id' => $role->id,
                'user_id' => $user->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('success', 'Usuário '.($action === 'assignRole' ? 'vinculado' : 'removido').' com sucesso!');
        } catch (Exception $exception) {
            Log::error('Erro ao alterar papel do usuário.', ['error' => $exception->getMessage()]);

            return back()->with('error', 'Erro ao alterar papel do usuário.');
        }
    }

    public function update(Role $role, Permission $permission)
    {
        try {
            $action = $role->permissions->contains($permission) ? 'bloquear' : 'liberar';

            $role->{$action === 'bloquear' ? 'revokePermissionTo' : 'givePermissionTo'}($permission);

            Log::info(ucfirst($action).' permissão para o papel.', [
                'role_id' => $role->id,
                'permission_id' => $permission->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()->route('role-permissions.index', ['role' => $role->id])
                ->with('success', 'Permissão '.($action === 'bloquear' ? 'bloqueada' : 'liberada').' com sucesso!');
        } catch (Exception $exception) {
            Log::notice('Permissão para o papel não editada.', [
                'error' => $exception->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()->withInput()->with('error', 'Permissão para o papel não editada!');
        }
    }

    // ✅ Novo método: listar usuários e vinculação com o papel
    public function manageUsers(Role $role)
    {
        $users = User::orderBy('name')->paginate(15);

        return view('role_permissions.users', [
            'role' => $role,
            'users' => $users,
        ]);
    }

    // ✅ Novo método: alternar papel para usuário
    public function toggleUserRole(Request $request, Role $role, User $user)
    {
        try {
            $action = $user->hasRole($role) ? 'remover' : 'atribuir';

            $user->{$action === 'remover' ? 'removeRole' : 'assignRole'}($role);

            Log::info(ucfirst($action).' papel do usuário.', [
                'role_id' => $role->id,
                'user_id' => $user->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('success', 'Papel '.($action === 'remover' ? 'removido' : 'atribuído').' com sucesso!');
        } catch (Exception $exception) {
            Log::error('Erro ao vincular papel ao usuário.', [
                'role_id' => $role->id,
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Erro ao alterar o papel do usuário.');
        }
    }
}
