<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Listar os usuários
    public function index(Request $request)
    {
        $users = User::when(
            $request->filled('search'),
            fn ($query) => $query->where(function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            })
        )
          ->orderBy('id', 'DESC')
          ->paginate(15)
          ->withQueryString();

        $statuses = UserStatus::all();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view
        return view('users.index', [
            'users' => $users,
            'statuses' => $statuses,
        ]);
    }

    // Visualizar os detalhes do usuário
    public function show(User $user)
    {
        // Salvar log
        Log::info('Visualizar o usuário.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

        // Carregar a view
        return view('users.show', ['user' => $user]);
    }

    // Carregar o formulário cadastrar novo usuário
    public function create()
    {
        // Recuperar os papéis
        $roles = Role::pluck('name')->all();

        // Recuperar os status do usuário
        $statuses = UserStatus::all(); // ou ->pluck('nome', 'id');

        // Carregar a view
        return view('users.create', ['roles' => $roles, 'statuses' => $statuses]);
    }

    // Cadastrar no banco de dados o novo usuário
    public function store(UserRequest $userRequest)
    {
        // Transformar o email em lowercase
        $userRequest->merge(['email' => strtolower($userRequest->email)]);

        // Capturar possíveis exceções durante a execução.
        try {
            // Cadastrar no banco de dados na tabela usuário
            $user = User::create([
                'name' => $userRequest->name,
                'email' => $userRequest->email,
                'password' => $userRequest->password,
            ]);

            // Verificar se veio algum papel selecionado
            if ($userRequest->filled('roles')) {
                // Verifica se todos os papéis existem (opcional, mas recomendado)
                $validRoles = Role::whereIn('name', $userRequest->roles)->pluck('name')->toArray();

                // Atribui os papéis válidos ao usuário
                $user->syncRoles($validRoles); // syncRoles() vários papeís ou assignRole() se for apenas um
            }

            // Salvar log
            Log::info('Usuário cadastrado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.show', ['user' => $user->id])->with('success', 'Usuário cadastrado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Usuário não cadastrado.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não cadastrado!');
        }
    }

    // Carregar o formulário editar usuário
    public function edit(User $user)
    {
        // Recuperar os papéis
        $roles = Role::pluck('name')->all();

        // Recuperar os papéis do usuário
        $userRoles = $user->roles->pluck('name')->toArray();

        // Recuperar os status do usuário
        $statuses = UserStatus::all(); // ou ->pluck('nome', 'id');

        // Carregar a view
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'statuses' => $statuses,
        ]);
    }

    // Editar no banco de dados o usuário
    public function update(UserRequest $userRequest, User $user)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Transformar o email em lowercase
            $userRequest->merge(['email' => strtolower($userRequest->email)]);

            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $userRequest->name,
                'email' => $userRequest->email,
                'user_status_id' => $userRequest->user_status_id,
            ]);

            // Se houver papéis enviados no request, sincroniza-os com o usuário
            if ($userRequest->filled('roles')) {
                // Verifica se todos os papéis existem (opcional, mas recomendado)
                $validRoles = Role::whereIn('name', $userRequest->roles)->pluck('name')->toArray();

                // Atribui os papéis válidos ao usuário
                $user->syncRoles($validRoles); // syncRoles() vários papeís ou assignRole() se for apenas um
            } else {
                // Se nenhum papel for enviado, remove todos os papéis do usuário
                $user->syncRoles([]);
            }

            // Salvar log
            Log::info('Usuário editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.show', ['user' => $user->id])->with('success', 'Usuário editado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Usuário não editado.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não editado!');
        }
    }

    // Carregar o formulário editar senha do usuário
    public function editPassword(User $user)
    {
        // Carregar a view
        return view('users.edit_password', ['user' => $user]);
    }

    // Editar no banco de dados a senha do usuário
    public function updatePassword(UserRequest $userRequest, User $user)
    {
        try {
            $user->update([
                'password' => bcrypt($userRequest->password),
            ]);

            Log::info('Senha do usuário editada.', [
                'user_id' => $user->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()->route('users.show', ['user' => $user->id])
                ->with('success', 'Senha do usuário editada com sucesso!');
        } catch (Exception $exception) {
            Log::error('Erro ao editar senha do usuário.', [
                'error' => $exception->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()->withInput()->with('error', 'Erro ao editar a senha do usuário!');
        }
    }

    // Excluir o curso do banco de dados
    public function destroy(User $user)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Excluir o registro do banco de dados
            $user->delete();

            // Salvar log
            Log::info('Usuário apagado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.index')->with('success', 'Usuário apagado com sucesso!');
        } catch (Exception $exception) {
            // Salvar log
            Log::notice('Usuário não editado.', ['error' => $exception->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não apagado!');
        }
    }

    public function updateStatus(UserRequest $userRequest, User $user)
    {
        $user->user_status_id = $userRequest->user_status_id;
        $user->save();

        if ($userRequest->ajax()) {
            return response()->json([
                'message' => 'Status atualizado com sucesso!',
                'status_name' => $user->userStatus->name,
            ]);
        }

        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
    }
}
