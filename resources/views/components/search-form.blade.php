@props([
    'baseRoute', // ex: 'user_statuses', 'cursos', 'usuarios'
    'placeholder' => 'Pesquisar...',
    'name' => 'search',
    'value' => request('search'),
])

@php
    $action = route($baseRoute . '.index'); // monta automaticamente rota.index
@endphp

<form action="{{ $action }}" method="GET" class="flex items-center gap-2 form-search">
    <input type="text" name="{{ $name }}" class="h-10 px-4 form-input" placeholder="{{ $placeholder }}"
        value="{{ $value }}" required />

    <div class="flex gap-2">
        {{-- Botão Pesquisar --}}
        <button type="submit" class="flex items-center justify-center h-10 gap-1 px-4 mt-1 btn-default">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0
                         5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <span>Pesquisar</span>
        </button>

        {{-- Botão Limpar --}}
        <a href="{{ $action }}" class="flex items-center justify-center h-10 gap-1 px-4 mt-1 btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9
                         m9.968-3.21c.342.052.682.107 1.022.166
                         m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077
                         H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79
                         m14.456 0a48.108 48.108 0 0 0-3.478-.397
                         m-12 .562c.34-.059.68-.114 1.022-.165
                         m0 0a48.11 48.11 0 0 1 3.478-.397
                         m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201
                         a51.964 51.964 0 0 0-3.32 0
                         c-1.18.037-2.09 1.022-2.09 2.201v.916
                         m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
            <span>Limpar</span>
        </a>
    </div>
</form>
