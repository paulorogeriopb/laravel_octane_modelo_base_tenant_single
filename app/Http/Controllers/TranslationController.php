<?php

declare(strict_types=1);
// app/Http/Controllers/TranslationController.php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $translations = Translation::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search) // busca exata por id
                  ->orWhere('group', 'like', "%{$search}%")
                  ->orWhere('key', 'like', "%{$search}%")
                  ->orWhere('text->pt', 'like', "%{$search}%")
                  ->orWhere('text->en', 'like', "%{$search}%")
                  ->orWhere('text->es', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('translations.index', compact('translations'));
}

    public function create()
    {
        return view('translations.create', [
            'translation' => new Translation(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:translations,key',
            'group' => 'nullable|string',
            'text' => 'required|array',
        ]);

        Translation::create($request->only(['key', 'group', 'text']));

        return redirect()->route('translations.index')->with('success', 'Tradução criada!');
    }

    public function edit(Translation $translation)
    {
        return view('translations.edit', ['data' => $translation]);
    }

    public function update(Request $request, Translation $translation)
    {
        $request->validate([
            'key' => 'required|string|unique:translations,key,'.$translation->id,
            'group' => 'nullable|string',
            'text' => 'required|array',
        ]);

        $translation->update($request->only(['key', 'group', 'text']));

        return redirect()->route('translations.index')->with('success', 'Tradução atualizada!');
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();

        return back()->with('success', 'Tradução removida!');
    }
}