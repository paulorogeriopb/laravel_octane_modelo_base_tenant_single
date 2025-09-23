@props([
    'title' => 'Nenhum registro encontrado.',
    'description' => 'Tente ajustar os filtros ou adicione um novo item.',
    'icon' => '  <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.75 9.75h.008v.008H9.75v-.008ZM14.25 9.75h.008v.008h-.008v-.008ZM12 15.75a6.75 6.75 0 1 0 0-13.5 6.75 6.75 0 0 0 0 13.5Z" />
                </svg>',
    'classes' => 'text-center ',
])

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <div class="flex justify-center mt-12">
            {!! $icon !!}
        </div>
    @endif
    <p class="text-lg font-medium">{{ $title }}</p>
    <p class="text-sm text-gray-400">{{ $description }}</p>
</div>
