<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Listar as permissões ou páginas
    public function index()
    {
        $permissions = Permission::orderBy('id', 'DESC')->paginate(15);

        Log::info('Listar permissões.', ['action_user_id' => Auth::id()]);

        return view('permissions.index', compact('permissions'));
    }

    // Visualizar os detalhes da permissão ou página
    public function show(Permission $permission)
    {
        Log::info('Visualizar permissão.', [
            'permission_id' => $permission->id,
            'action_user_id' => Auth::id()
        ]);

        return view('permissions.show', compact('permission'));
    }

    // Formulário para criar nova permissão
    public function create()
    {
        return view('permissions.create');
    }

    // Armazenar nova permissão
    public function store(PermissionRequest $request)
    {
        $permission = Permission::create([
            'title' => $request->title,
            'name' => $request->name,
        ]);

        Log::info('Permissão cadastrada.', [
            'permission_id' => $permission->id,
            'action_user_id' => Auth::id()
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissão cadastrada com sucesso!');
    }

    // Formulário para editar permissão
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Atualizar permissão existente
    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'title' => $request->title,
            'name' => $request->name,
        ]);

        Log::info('Permissão editada.', [
            'permission_id' => $permission->id,
            'action_user_id' => Auth::id()
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissão editada com sucesso!');
    }

    // Excluir permissão
    public function destroy(Permission $permission)
    {
        $permission->delete();

        Log::info('Permissão apagada.', [
            'permission_id' => $permission->id,
            'action_user_id' => Auth::id()
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissão apagada com sucesso!');
    }
}