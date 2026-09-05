@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between sm:justify-end gap-1.5">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 text-xs cursor-not-allowed" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold shadow-xs tap-effect transition" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-400 text-xs font-bold" aria-disabled="true">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand text-white text-xs font-bold shadow-sm shadow-brand/30">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold shadow-xs tap-effect transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold shadow-xs tap-effect transition" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 text-xs cursor-not-allowed" aria-disabled="true" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif
