@if ($paginator->hasPages())
    <nav class="mt-4 flex items-center justify-between gap-3" aria-label="Paginación">
        @if ($paginator->onFirstPage())
            <span class="btn opacity-50" aria-disabled="true">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn">Anterior</a>
        @endif

        <span class="text-sm text-muted">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn">Siguiente</a>
        @else
            <span class="btn opacity-50" aria-disabled="true">Siguiente</span>
        @endif
    </nav>
@endif
