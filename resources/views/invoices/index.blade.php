@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
    <x-page-header title="Facturas">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary shrink-0">Nueva factura</a>
    </x-page-header>

    <dl class="mb-4 grid grid-cols-2 gap-3">
        <div class="card px-4 py-3">
            <dt class="text-sm text-muted">Pendiente de cobro</dt>
            <dd class="mt-0.5 text-lg font-bold"><x-money :amount="$pending" /></dd>
        </div>
        <div class="card px-4 py-3">
            <dt class="text-sm text-muted">Cobrado</dt>
            <dd class="mt-0.5 text-lg font-bold"><x-money :amount="$paid" /></dd>
        </div>
    </dl>

    <form method="GET" action="{{ route('invoices.index') }}" role="search" class="mb-3 flex gap-2">
        @if ($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <input type="search" name="q" value="{{ $search }}" class="input" placeholder="Buscar por número" aria-label="Buscar por número" autocapitalize="characters" enterkeyhint="search">
        <button type="submit" class="btn shrink-0">Buscar</button>
    </form>

    <nav class="-mx-4 mb-4 flex gap-2 overflow-x-auto px-4 pb-1" aria-label="Filtrar por estado">
        <a href="{{ route('invoices.index', array_filter(['q' => $search])) }}" class="chip" @if (! $status) aria-current="page" @endif>Todas</a>
        @foreach (\App\Models\Invoice::STATUSES as $value => $label)
            <a href="{{ route('invoices.index', array_filter(['q' => $search, 'status' => $value])) }}" class="chip" @if ($status === $value) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>

    @if ($invoices->isEmpty())
        <div class="card px-6 py-10 text-center">
            @if ($search !== '' || $status)
                <p class="font-medium">No hay facturas con estos filtros.</p>
                <a href="{{ route('invoices.index') }}" class="btn mt-4">Quitar filtros</a>
            @elseif ($hasClients)
                <p class="font-medium">Todavía no hay facturas.</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary mt-4">Crear la primera</a>
            @else
                <p class="font-medium">Todavía no hay facturas.</p>
                <p class="mt-1 text-sm text-muted">Para emitir una factura necesitas un cliente.</p>
                <a href="{{ route('clients.create') }}" class="btn btn-primary mt-4">Crear un cliente</a>
            @endif
        </div>
    @else
        <ul class="card divide-y divide-line overflow-hidden">
            @foreach ($invoices as $invoice)
                <li>
                    <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-canvas">
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $invoice->number }}</p>
                            <p class="truncate text-sm text-muted">{{ $invoice->client->name }}</p>
                            <p class="text-sm text-muted">{{ $invoice->issued_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex shrink-0 flex-col items-end gap-1">
                            <x-money :amount="$invoice->amount" class="font-semibold" />
                            <x-status-badge :invoice="$invoice" />
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        {{ $invoices->links('partials.pagination') }}
    @endif
@endsection
