@props(['title', 'back' => null])

<div class="mb-5">
    @if ($back)
        <a href="{{ $back }}" class="-ml-1 mb-1 inline-flex min-h-9 items-center gap-1 pr-2 text-sm font-medium text-muted">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>
    @endif

    <div class="flex items-center justify-between gap-3">
        <h1 class="min-w-0 text-2xl font-bold tracking-tight break-words">{{ $title }}</h1>
        {{ $slot }}
    </div>
</div>
