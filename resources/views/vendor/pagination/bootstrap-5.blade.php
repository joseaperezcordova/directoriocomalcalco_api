@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-end">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
            </li>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            {{-- Separador "..." --}}
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Siguiente</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Siguiente</span>
            </li>
        @endif

    </ul>

    <div class="text-muted small mt-1 text-end">
        Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }}
        de {{ $paginator->total() }} resultados
    </div>
</nav>
@endif
