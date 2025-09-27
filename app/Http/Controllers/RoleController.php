<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Listar os papéis
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        // $roles = Role::orderBy('id', 'DESC')->paginate(10);

        $roles = Role::when(
            $request->filled('search'),
            fn ($query) => $query->where(function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
        )
          ->orderBy('id', 'DESC')
          ->paginate(15)
          ->withQueryString();

        // Salvar log
        Log::info('Listar os papéis.', ['action_user_id' => Auth::id()]);

        // Carregar a view
        return view('roles.index', ['roles' => $roles]);
    }

    // Visualizar os detalhes do papel
    public function show(Role $role)
    {
        // Salvar log
        Log::info('Visualizar o papel.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

        // Carregar a view
        return view('roles.show', ['role' => $role]);
    }

    // Carregar o formulário cadastrar novo papel
    public function create()
    {
        // Carregar a view
        return view('roles.create');
    }

    // Cadastrar no banco de dados o novo papel
    public function store(RoleRequest $roleRequest)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Cadastrar no banco de dados na tabela role
            $role = Role::create([
                'name' => $roleRequest->name,
            ]);

            // Permissões que serão atribuídas ao novo papel
            $permissions = [
                'dashboard',
                'profile-show',
                'profile-edit',
                'profile-password-edit',
            ];

            // Atribuir as permissões ao papel
            $role->givePermissionTo($permissions);

            // Salvar log
            Log::info('Papel cadastrado.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            // return redirect()->route('roles.index', ['role' => $role->id])->with('success', 'Papel cadastrado com sucesso!');
            return redirect()->route('roles.index')->with('success', 'Papel cadastrado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Papel não cadastrado.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Papel não cadastrado!');
        }
    }

    // Carregar o formulário editar papel
    public function edit(Role $role)
    {
        // Carregar a view
        return view('roles.edit', ['role' => $role]);
    }

    // Editar no banco de dados o papel
    public function update(RoleRequest $roleRequest, Role $role)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Editar as informações do registro no banco de dados
            $role->update([
                'name' => $roleRequest->name,
            ]);

            // Salvar log
            Log::info('Papel editado.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('roles.index', ['role' => $role->id])->with('success', 'Papel editado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Papel não editado.', ['error' => $exception->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Papel não editado!');
        }
    }

    // Excluir o papel do banco de dados
    public function destroy(Role $role)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Excluir o registro do banco de dados
            $role->delete();

            // Salvar log
            Log::info('Papel apagado.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('roles.index')->with('success', 'Papel apagado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Papel não apagado.', ['error' => $exception->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Papel não apagado!');
        }
    }
}