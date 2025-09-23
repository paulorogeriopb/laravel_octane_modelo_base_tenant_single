@props([
    'baseRoute', // ex: 'cursos', 'usuarios'
])

@can($baseRoute . '.create')
    <a href="{{ route($baseRoute . '.create') }}" class="flex items-center space-x-1 btn-default align-icon-btn">
        <!-- Ícone plus-circle (Heroicons) -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>Cadastrar</span>
    </a>
@endcan
