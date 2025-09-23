@props([
    'baseRoute', // ex: 'cursos', 'usuarios'
    'entity' => null, // opcional, caso precise do id
])

@can($baseRoute . '.index')
    <a href="{{ route($baseRoute . '.index') }}" class="flex items-center space-x-1 btn-default align-icon-btn">
        <!-- Ícone plus-circle (Heroicons) -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
        </svg>
        <span>Listar</span>
    </a>
@endcan
