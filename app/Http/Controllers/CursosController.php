<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursosController extends Controller
{
    protected $model = Curso::class;
    protected $view = 'cursos';
    protected $route = 'cursos';

    // Lista cursos paginados
    public function index(Request $request)
    {
        $data = Curso::when(
                $request->filled('search'),
                fn($query) => $query->where('name', 'like', '%'.$request->search.'%')
            )
            //->where('user_id', Auth::id()) // filtra apenas do usuário logado
            ->orderBy('id', 'DESC')
            ->paginate(15)
            ->withQueryString();

        return view($this->view.'.index', ['data' => $data]);
    }

    // Formulário para criar curso
    public function create()
    {
        return view($this->view.'.create');
    }

    // Armazenar novo curso
    public function store(CursoRequest $cursoRequest)
    {
        $data = $cursoRequest->validated();

        Curso::create($data);

        return redirect()->route($this->route.'.index')
                         ->with('success', __('mensagens.created'));
    }

    // Formulário para editar curso
    public function edit(int $id)
    {
        $data = Curso::findOrFail($id);

        return view($this->view.'.edit', ['data' => $data]);
    }

    // Atualizar curso existente
    public function update(CursoRequest $cursoRequest, int $id)
    {
        $data = $cursoRequest->validated();
        $curso = Curso::findOrFail($id);
        $curso->update($data);

        return redirect()->route($this->route.'.index')
                         ->with('success', __('mensagens.updated'));
    }

    // Deletar curso
    public function destroy(int $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return redirect()->route($this->route.'.index')
                         ->with('success', __('mensagens.deleted'));
    }
}