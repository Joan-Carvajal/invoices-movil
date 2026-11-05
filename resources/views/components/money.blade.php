@props(['amount'])

<span {{ $attributes->merge(['class' => 'tabular-nums']) }}>{{ number_format((float) $amount, 2, ',', '.') }} €</span>
