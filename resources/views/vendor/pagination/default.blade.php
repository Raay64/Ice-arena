@if ($paginator->hasPages())
    <nav style="display: flex; justify-content: center; gap: 8px;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 8px 12px; background: #f1f5f9; color: #94a3b8; border-radius: 6px;">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding: 8px 12px; background: white; color: #334155; border-radius: 6px; text-decoration: none; border: 1px solid #e2e8f0;">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span style="padding: 8px 12px; color: #64748b;">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding: 8px 16px; background: #2563eb; color: white; border-radius: 6px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 8px 16px; background: white; color: #334155; border-radius: 6px; text-decoration: none; border: 1px solid #e2e8f0;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding: 8px 12px; background: white; color: #334155; border-radius: 6px; text-decoration: none; border: 1px solid #e2e8f0;">→</a>
        @else
            <span style="padding: 8px 12px; background: #f1f5f9; color: #94a3b8; border-radius: 6px;">→</span>
        @endif
    </nav>
@endif
