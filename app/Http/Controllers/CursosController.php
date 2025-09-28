<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use App\Tenant\ManagerTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursosController extends Controller
{
    protected string $model = Curso::class;
    protected string $view = 'cursos';
    protected string $route = 'cursos';

    // Lista cursos paginados
    public function index(Request $request)
    {
        $data = ($this->model)::when(
                $request->filled('search'),
                fn($query) => $query->where('name', 'like', '%' . $request->search . '%')
            )
            ->orderBy('id', 'DESC')
            ->paginate(15)
            ->withQueryString();

        return view("{$this->view}.index", compact('data'));
    }

    // Formulário para criar curso
    public function create()
    {
        return view("{$this->view}.create");
    }

    // Armazenar novo curso
    public function store(CursoRequest $cursoRequest)
    {
        $data = $cursoRequest->validated();

        if ($cursoRequest->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($cursoRequest->file('image'), $cursoRequest->name);
        }

        ($this->model)::create($data);

        return redirect()
            ->route("{$this->route}.index")
            ->with('success', __('mensagens.created'));
    }

    // Formulário para editar curso
    public function edit(int $id)
    {
        $data = ($this->model)::findOrFail($id);

        return view("{$this->view}.edit", compact('data'));
    }

    // Atualizar curso existente
    public function update(CursoRequest $cursoRequest, Curso $curso)
    {
        $data = $cursoRequest->validated();

        if ($cursoRequest->hasFile('image')) {
            // Deleta imagem antiga, se existir
            if ($curso->image && Storage::disk('tenant')->exists($curso->image)) {
                Storage::disk('tenant')->delete($curso->image);
            }

            $data['image'] = $this->handleImageUpload($cursoRequest->file('image'), $cursoRequest->name);
        }

        $curso->update($data);

        return redirect()
            ->route("{$this->route}.index")
            ->with('success', __('mensagens.updated'));
    }

    // Deletar curso
    public function destroy(int $id)
    {
        $curso = ($this->model)::findOrFail($id);

        // Deleta imagem associada
        if ($curso->image && Storage::disk('tenant')->exists($curso->image)) {
            Storage::disk('tenant')->delete($curso->image);
        }

        $curso->delete();

        return redirect()
            ->route("{$this->route}.index")
            ->with('success', __('mensagens.deleted'));
    }

    /**
     * Upload de imagem centralizado.
     */
    protected function handleImageUpload($file, string $name): string
    {
        $tenant = app(ManagerTenant::class)->getTenant();
        $tenantFolder = $tenant->uuid;
        $datePath = now()->format('Y/m/d');
        $fullPath = "{$tenantFolder}/{$datePath}";

        if (!Storage::disk('tenant')->exists($fullPath)) {
            Storage::disk('tenant')->makeDirectory($fullPath, 0755, true);
        }

        $extension = $file->extension();
        $fileName = Str::kebab($name) . '-' . time() . '.' . $extension;

        $file->storeAs($fullPath, $fileName, 'tenant');

        return "{$fullPath}/{$fileName}";
    }
}