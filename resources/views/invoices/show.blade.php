@extends('layouts.app')

@section('title', 'Factura '.$invoice->number)

@section('content')
    <h1>Factura {{ $invoice->number }}</h1>

    <dl>
        <dt>Cliente</dt>
        <dd>{{ $invoice->client->name }}</dd>

        <dt>Fecha de emisión</dt>
        <dd>{{ $invoice->issued_at->format('d/m/Y') }}</dd>

        <dt>Importe</dt>
        <dd>{{ number_format((float) $invoice->amount, 2, ',', '.') }} €</dd>

        <dt>Estado</dt>
        <dd>{{ \App\Models\Invoice::STATUSES[$invoice->status] }}</dd>

        @if ($invoice->notes)
            <dt>Notas</dt>
            <dd>{!! nl2br($invoice->notes) !!}</dd>
        @endif
    </dl>

    @if ($invoice->attachment_path)
        <p><a href="{{ route('invoices.attachment', $invoice) }}">Descargar {{ $invoice->attachment_name }}</a></p>
    @endif

    <p>
        <a href="{{ route('invoices.edit', $invoice) }}">Editar</a>
    </p>

    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
@endsection