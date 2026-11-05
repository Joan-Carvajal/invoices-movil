@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <x-page-header title="Clientes">
        <a href="{{ route('clients.create') }}" class="btn btn-primary shrink-0">Nuevo cliente</a>
    </x-page-header>

    @if ($clients->isEmpty())
        <div class="card px-6 py-10 text-center">
            <p class="font-medium">Todavía no tienes clientes.</p>
            <p class="mt-1 text-sm text-muted">Crea uno para poder emitirle facturas.</p>
        </div>
    @else
        <ul class="card divide-y divide-line overflow-hidden">
            @foreach ($clients as $client)
                <li>
                    <a href="{{ route('clients.edit', $client) }}" class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-canvas">
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $client->name }}</p>
                            <p class="truncate text-sm text-muted">{{ collect([$client->email, $client->tax_id])->filter()->join(' · ') ?: 'Sin datos de contacto' }}</p>
                        </div>
                        <span class="shrink-0 text-sm text-muted">{{ trans_choice(':count factura|:count facturas', $client->invoices_count) }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
