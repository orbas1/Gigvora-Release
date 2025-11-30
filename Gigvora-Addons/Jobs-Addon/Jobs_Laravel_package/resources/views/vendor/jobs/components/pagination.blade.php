@if(isset($paginator) && $paginator->hasPages())
    <nav aria-label="Pagination" class="mt-4 flex items-center justify-center gap-2 flex-wrap text-sm">
        @if ($paginator->onFirstPage())
            <span class="gv-btn gv-btn-ghost gv-btn-sm opacity-50" aria-disabled="true">&lsaquo;</span>
        @else
            <a class="gv-btn gv-btn-ghost gv-btn-sm" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
        @endif

        @foreach ($paginator->elements() as $element)
            @if (is_string($element))
                <span class="gv-btn gv-btn-ghost gv-btn-sm opacity-50">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="gv-btn gv-btn-secondary gv-btn-sm" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="gv-btn gv-btn-ghost gv-btn-sm" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="gv-btn gv-btn-ghost gv-btn-sm" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
        @else
            <span class="gv-btn gv-btn-ghost gv-btn-sm opacity-50" aria-disabled="true">&rsaquo;</span>
        @endif
    </nav>
@endif
