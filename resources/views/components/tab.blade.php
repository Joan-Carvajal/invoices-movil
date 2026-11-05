@props(['href', 'active' => false, 'label'])

<a href="{{ $href }}"
   @if ($active) aria-current="page" @endif
   @class(['flex flex-col items-center justify-center gap-0.5 text-xs font-medium', 'text-brand' => $active, 'text-muted' => ! $active])>
    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{{ $slot }}</svg>
    {{ $label }}
</a>
