<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use App\Tenant\ManagerTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if ($cursoRequest->hasFile('image') && $cursoRequest->file('image')->isValid()) {

            $tenant = app(ManagerTenant::class)->getTenant();
            $tenantFolder = $tenant->uuid;

            // Estrutura por ano/mês/dia
            $datePath = now()->format('Y/m/d');
            $fullPath = "{$tenantFolder}/{$datePath}";

            // Cria a pasta se não existir
            if (!Storage::disk('tenant')->exists($fullPath)) {
                Storage::disk('tenant')->makeDirectory($fullPath, 0755, true);
            }

            $extension = $cursoRequest->image->extension();
            $fileName = Str::kebab($cursoRequest->name) . '-' . time() . '.' . $extension;

            // Salva o arquivo na pasta do tenant com data
            $cursoRequest->image->storeAs($fullPath, $fileName, 'tenant');

            // Salva caminho relativo no DB
            $data['image'] = "{$fullPath}/{$fileName}";
        }

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
    public function update(CursoRequest $cursoRequest, Curso $curso)
    {
        $data = $cursoRequest->validated();

        if ($cursoRequest->hasFile('image') && $cursoRequest->file('image')->isValid()) {

            $tenant = app(ManagerTenant::class)->getTenant();
            $tenantFolder = $tenant->uuid;

            // Cria pasta do tenant
            if (!Storage::disk('tenant')->exists($tenantFolder)) {
                Storage::disk('tenant')->makeDirectory($tenantFolder);
            }

            // Cria subpastas por ano/mês/dia igual WordPress
            $subFolder = date('Y/m/d');
            $fullPath = "{$tenantFolder}/{$subFolder}";
            if (!Storage::disk('tenant')->exists($fullPath)) {
                Storage::disk('tenant')->makeDirectory($fullPath);
            }

            // Nome do arquivo
            $extension = $cursoRequest->image->extension();
            $fileName = Str::kebab($cursoRequest->name) . '-' . time() . '.' . $extension;

            // Salva dentro da pasta do tenant com subpastas de data
            $cursoRequest->image->storeAs($fullPath, $fileName, 'tenant');

            // Deleta imagem antiga (opcional)
            if ($curso->image && Storage::disk('tenant')->exists($curso->image)) {
                Storage::disk('tenant')->delete($curso->image);
            }

            // Atualiza o path no banco
            $data['image'] = "{$fullPath}/{$fileName}";
        }

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