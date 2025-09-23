<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Listar as permissões ou páginas
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        //$permissions = Permission::orderBy('name', 'ASC')->paginate(10);
        $permissions = Permission::when(
            $request->filled('search'),
            fn ($query) => $query->where(function ($q) use ($request): void {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name', 'like', '%'.$request->search.'%');
            })
        )
            ->orderBy('id', 'DESC')
            ->paginate(15)
            ->withQueryString();

        // Salvar log
        Log::info('Listar as permissões.', ['action_user_id' => Auth::id()]);

        // Carregar a view
        return view('permissions.index', ['permissions' => $permissions]);
    }

    // Visualizar os detalhes da permissão ou página
    public function show(Permission $permission)
    {
        // Salvar log
        Log::info('Visualizar a permissão.', ['permission_id' => $permission->id, 'action_user_id' => Auth::id()]);

        // Carregar a view
        return view('permissions.show', ['permission' => $permission]);
    }

    // Carregar o formulário cadastrar nova permissão ou página
    public function create()
    {
        // Carregar a view
        return view('permissions.create');
    }

    // Cadastrar no banco de dados o nova permissão ou página
    public function store(PermissionRequest $permissionRequest)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Cadastrar no banco de dados na tabela permissão
            $permission = Permission::create([
                'title' => $permissionRequest->title,
                'name' => $permissionRequest->name,
            ]);

            // Salvar log
            Log::info('Permissão cadastrada.', ['permission_id' => $permission->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('permissions.index', ['permission' => $permission->id])->with('success', 'Permissão cadastrada com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Permissão não cadastrada.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Permissão não cadastrada!');
        }
    }

    // Carregar o formulário editar permissão ou página
    public function edit(Permission $permission)
    {
        // Carregar a view
        return view('permissions.edit', ['permission' => $permission]);
    }

    // Editar no banco de dados a permissão ou página
    public function update(PermissionRequest $permissionRequest, Permission $permission)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Editar as informações do registro no banco de dados
            $permission->update([
                'title' => $permissionRequest->title,
                'name' => $permissionRequest->name,
            ]);

            // Salvar log
            Log::info('Permissão editada.', ['permission_id' => $permission->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('permissions.index', ['permission' => $permission->id])->with('success', 'Permissão editada com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Permissão não editada.', ['error' => $exception->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Permissão não editada!');
        }
    }

    // Excluir a permissão ou página do banco de dados
    public function destroy(Permission $permission)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Excluir o registro do banco de dados
            $permission->delete();

            // Salvar log
            Log::info('Permissão apagada.', ['permission_id' => $permission->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('permissions.index')->with('success', 'Permissão apagada com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Permissão não apagada.', ['error' => $exception->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Permissão não apagada!');
        }
    }
}
