@extends('layouts.app')

@section('title', $invoice->exists ? 'Editar factura' : 'Nueva factura')

@section('content')
    <x-page-header :title="$invoice->exists ? 'Editar factura' : 'Nueva factura'" :back="$invoice->exists ? route('invoices.show', $invoice) : route('invoices.index')" />

    @if ($clients->isEmpty())
        <div class="card px-6 py-10 text-center">
            <p class="font-medium">Para emitir una factura necesitas un cliente.</p>
            <a href="{{ route('clients.create') }}" class="btn btn-primary mt-4">Crear un cliente</a>
        </div>
    @else
        <form method="POST" action="{{ $invoice->exists ? route('invoices.update', $invoice) : route('invoices.store') }}" enctype="multipart/form-data" class="card space-y-4 p-4">
            @csrf
            @if ($invoice->exists)
                @method('PUT')
            @endif

            <x-field name="client_id" label="Cliente">
                <select id="client_id" name="client_id" class="input" @error('client_id') aria-invalid="true" @enderror required>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('client_id', $invoice->client_id) == $client->id)>{{ $client->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <x-field name="number" label="Número">
                <input id="number" name="number" class="input" value="{{ old('number', $invoice->number) }}" autocapitalize="characters" autocomplete="off" @error('number') aria-invalid="true" @enderror required>
            </x-field>

            <div class="grid grid-cols-2 gap-3">
                <x-field name="amount" label="Importe (€)">
                    <input id="amount" type="number" step="0.01" min="0" inputmode="decimal" name="amount" class="input" value="{{ old('amount', $invoice->amount) }}" @error('amount') aria-invalid="true" @enderror required>
                </x-field>

                <x-field name="status" label="Estado">
                    <select id="status" name="status" class="input" @error('status') aria-invalid="true" @enderror>
                        @foreach (\App\Models\Invoice::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $invoice->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-field>
            </div>

            <x-field name="issued_at" label="Fecha de emisión">
                <input id="issued_at" type="date" name="issued_at" class="input" value="{{ old('issued_at', $invoice->issued_at?->format('Y-m-d')) }}" @error('issued_at') aria-invalid="true" @enderror required>
            </x-field>

            <x-field name="notes" label="Notas">
                <textarea id="notes" name="notes" rows="4" class="input" @error('notes') aria-invalid="true" @enderror>{{ old('notes', $invoice->notes) }}</textarea>
            </x-field>

            @if (\App\Support\NativeApp::android())
                <p class="rounded-xl bg-neutral-soft px-3 py-2 text-sm text-muted">Podrás adjuntar una foto desde el detalle de la factura.</p>
            @else
                <x-field name="attachment" label="Adjunto" :hint="$invoice->attachment_name ? 'Actual: '.$invoice->attachment_name.'. Si eliges otro, se sustituye.' : 'PDF, JPG o PNG, hasta 5 MB.'">
                    <input id="attachment" type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" class="block w-full text-sm text-muted file:mr-3 file:min-h-11 file:cursor-pointer file:rounded-xl file:border-0 file:bg-neutral-soft file:px-4 file:font-semibold file:text-ink">
                </x-field>
            @endif

            <button type="submit" class="btn btn-primary w-full">Guardar</button>
        </form>
    @endif
@endsection
