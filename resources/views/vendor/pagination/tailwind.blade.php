@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Botão Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn-hover">Anterior</a>
        @endif

        {{-- Números --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-btn-active"> {{ $page }} </span>
                    @else
                        <a href="{{ $url }}" class="pagination-btn-hover"> {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Botão Próximo --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn-hover">Próximo</a>
        @else
            <span class="pagination-btn">Próximo</span>
        @endif
    </nav>
@endif
