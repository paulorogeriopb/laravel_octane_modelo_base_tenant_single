<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CursosController extends Controller
{
    protected $model = Curso::class;

    protected $view = 'cursos';

    protected $route = 'cursos';

    // Lista cursos paginados
    public function index(Request $request)
    {
        try {
            $data = Curso::when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($q) use ($request): void {
                    $q->where('name', 'like', '%'.$request->search.'%');
                })
            )
                ->orderBy('id', 'DESC')
                ->paginate(15)
                ->withQueryString();

            // $data = Curso::orderBy('created_at', 'desc')->paginate(2);

            return view($this->view.'.index', ['data' => $data]);

        } catch (Exception $exception) {
            $this->logError('Erro ao listar cursos', $exception);

            return redirect()->back()->with('error', __('mensagens.server_error'));
        }
    }

    // Formulário para criar curso
    public function create()
    {
        try {
            return view($this->view.'.create');
        } catch (Exception $exception) {
            $this->logError('Erro ao abrir formulário de criação de curso', $exception);

            return redirect()->route($this->route.'.index')->with('error', __('mensagens.server_error'));
        }
    }

    // Armazenar novo curso
    public function store(CursoRequest $cursoRequest)
    {
        try {
            $data = $cursoRequest->validated();
            Curso::create($data);

            return redirect()->route('cursos.index')->with('success', __('mensagens.created'));
        } catch (Exception $exception) {
            Log::error('Erro ao criar curso: '.$exception->getMessage(), ['exception' => $exception]);

            return redirect()->back()->withInput()->with('error', __('mensagens.server_error'));
        }
    }

    // Formulário para editar curso
    public function edit($id)
    {
        try {
            $data = Curso::findOrFail($id);

            return view($this->view.'.edit', ['data' => $data]);
        } catch (Exception $exception) {
            $this->logError('Erro ao abrir edição de curso', $exception, ['id' => $id]);

            return redirect()->route($this->route.'.index')->with('error', __('mensagens.server_error'));
        }
    }

    // Atualizar curso existente
    public function update(CursoRequest $cursoRequest, $id)
    {
        try {
            $data = $cursoRequest->validated();
            $curso = Curso::findOrFail($id);
            $curso->update($data);

            return redirect()->route($this->route.'.index')->with('success', __('mensagens.updated'));
        } catch (Exception $exception) {
            $this->logError(__('mensagens.logs.update_fail'), $exception, $cursoRequest, ['id' => $id]);

            return redirect()->back()->withInput()->with('error', __('mensagens.server_error'));
        }
    }

    // Deletar curso
    public function destroy($id)
    {
        try {
            $curso = Curso::findOrFail($id);
            $curso->delete();

            return redirect()->route($this->route.'.index')->with('success', __('mensagens.deleted'));
        } catch (Exception $exception) {
            $this->logError(__('mensagens.logs.delete_fail'), $exception, null, ['id' => $id]);

            return redirect()->back()->with('error', __('mensagens.server_error'));
        }
    }

    // Função privada para logar erros centralizadamente
    private function logError(string $message, Exception $exception, array|CursoRequest|null $request = null, array $extra = []): void
    {
        $user = Auth::user();

        $context = array_merge([
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip' => $request?->ip() ?? request()->ip(),
            'user_agent' => $request?->userAgent() ?? request()->userAgent(),
            'url' => $request?->fullUrl() ?? request()->fullUrl(),
            'route' => $request?->route()?->getName() ?? request()->route()?->getName(),
            'exception' => $exception,
        ], $extra);

        Log::error($message.': '.$exception->getMessage(), $context);
    }
}
