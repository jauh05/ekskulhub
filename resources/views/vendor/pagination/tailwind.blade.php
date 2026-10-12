{{-- Override pagination Laravel dengan token tema (bukan palet gray bawaan). --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        {{-- Mobile --}}
        <div class="flex items-center justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-lg border border-outline-variant bg-surface-container-low px-4 py-2 text-label-md text-outline">{!! __('pagination.previous') !!}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-lg border border-outline-variant bg-surface px-4 py-2 text-label-md text-on-surface transition-colors hover:bg-surface-container">{!! __('pagination.previous') !!}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-lg border border-outline-variant bg-surface px-4 py-2 text-label-md text-on-surface transition-colors hover:bg-surface-container">{!! __('pagination.next') !!}</a>
            @else
                <span class="inline-flex items-center rounded-lg border border-outline-variant bg-surface-container-low px-4 py-2 text-label-md text-outline">{!! __('pagination.next') !!}</span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden w-full items-center justify-between gap-4 sm:flex">
            <p class="text-body-sm text-secondary">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-on-surface">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-on-surface">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-semibold text-on-surface">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>

            <div class="inline-flex items-center gap-1">
                {{-- Halaman sebelumnya --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex size-9 items-center justify-center rounded-lg text-outline">
                        <x-icon name="chevron-left" class="size-[18px]" />
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="inline-flex size-9 items-center justify-center rounded-lg text-on-surface transition-colors hover:bg-surface-container">
                        <x-icon name="chevron-left" class="size-[18px]" />
                    </a>
                @endif

                {{-- Nomor halaman --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="inline-flex size-9 items-center justify-center text-body-sm text-secondary">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex size-9 items-center justify-center rounded-lg bg-primary-container text-label-md text-on-primary-container">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" class="inline-flex size-9 items-center justify-center rounded-lg text-label-md text-on-surface transition-colors hover:bg-surface-container">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Halaman berikutnya --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="inline-flex size-9 items-center justify-center rounded-lg text-on-surface transition-colors hover:bg-surface-container">
                        <x-icon name="chevron-right" class="size-[18px]" />
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex size-9 items-center justify-center rounded-lg text-outline">
                        <x-icon name="chevron-right" class="size-[18px]" />
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
