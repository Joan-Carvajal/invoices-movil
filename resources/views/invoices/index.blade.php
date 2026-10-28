@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
    <h1>Facturas</h1>

    <form method="GET" action="{{ route('invoices.index') }}">
        <input type="search" name="q" value="{{ $search }}" placeholder="Buscar por número">
        <button type="submit">Buscar</button>
    </form>

    <p><a href="{{ route('invoices.create') }}">Nueva factura</a></p>

    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Importe</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->number }}</a></td>
                    <td>{{ $invoice->client->name }}</td>
                    <td>{{ $invoice->issued_at->format('d/m/Y') }}</td>
                    <td>{{ number_format((float) $invoice->amount, 2, ',', '.') }} €</td>
                    <td>{{ \App\Models\Invoice::STATUSES[$invoice->status] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay facturas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $invoices->links() }}
@endsection