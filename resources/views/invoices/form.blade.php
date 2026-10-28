@extends('layouts.app')

@section('title', $invoice->exists ? 'Editar factura' : 'Nueva factura')

@section('content')
    <h1>{{ $invoice->exists ? 'Editar factura' : 'Nueva factura' }}</h1>

    <form method="POST" action="{{ $invoice->exists ? route('invoices.update', $invoice) : route('invoices.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($invoice->exists)
            @method('PUT')
        @endif

        <label for="client_id">Cliente</label>
        <select id="client_id" name="client_id" required>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $invoice->client_id) == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>

        <label for="number">Número</label>
        <input id="number" name="number" value="{{ old('number', $invoice->number) }}" required>

        <label for="amount">Importe</label>
        <input id="amount" type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $invoice->amount) }}" required>

        <label for="status">Estado</label>
        <select id="status" name="status">
            @foreach (\App\Models\Invoice::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $invoice->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <label for="issued_at">Fecha de emisión</label>
        <input id="issued_at" type="date" name="issued_at" value="{{ old('issued_at', $invoice->issued_at?->format('Y-m-d')) }}" required>

        <label for="notes">Notas</label>
        <textarea id="notes" name="notes" rows="5">{{ old('notes', $invoice->notes) }}</textarea>

        <label for="attachment">Adjunto (PDF, JPG o PNG, hasta 5 MB)</label>
        <input id="attachment" type="file" name="attachment">

        <p><button type="submit">Guardar</button></p>
    </form>
@endsection