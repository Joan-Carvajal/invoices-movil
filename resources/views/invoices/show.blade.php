@extends('layouts.app')

@section('title', 'Factura '.$invoice->number)

@section('content')
    <x-page-header :title="'Factura '.$invoice->number" :back="route('invoices.index')" />

    <section class="card mb-4 p-4">
        <div class="flex items-start justify-between gap-3">
            <x-money :amount="$invoice->amount" class="text-3xl font-bold tracking-tight" />
            <x-status-badge :invoice="$invoice" />
        </div>

        <dl class="mt-4 grid grid-cols-2 gap-4 border-t border-line pt-4 text-sm">
            <div>
                <dt class="text-muted">Cliente</dt>
                <dd class="mt-0.5 font-medium break-words">{{ $invoice->client->name }}</dd>
            </div>
            <div>
                <dt class="text-muted">Fecha de emisión</dt>
                <dd class="mt-0.5 font-medium">{{ $invoice->issued_at->format('d/m/Y') }}</dd>
            </div>
            @if ($invoice->notes)
                <div class="col-span-2">
                    <dt class="text-muted">Notas</dt>
                    <dd class="mt-0.5 break-words whitespace-pre-line">{{ $invoice->notes }}</dd>
                </div>
            @endif
        </dl>
    </section>

    <section class="card mb-4 space-y-3 p-4">
        <h2 class="text-sm font-semibold">Adjunto</h2>

        @if ($invoice->attachment_path)
            <a href="{{ route('invoices.attachment', $invoice) }}" class="btn w-full">
                <span class="truncate">{{ \App\Support\NativeApp::running() ? 'Compartir' : 'Descargar' }} {{ $invoice->attachment_name }}</span>
            </a>
        @else
            <p class="text-sm text-muted">Esta factura no tiene adjunto.</p>
        @endif

        @if (\App\Support\NativeApp::running())
            <form method="POST" action="{{ route('invoices.photo', $invoice) }}" data-device-photo>
                @csrf
                <input type="hidden" name="path">
            </form>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" class="btn" data-capture="camera">Hacer foto</button>
                <button type="button" class="btn" data-capture="gallery">Galería</button>
            </div>

            @if ($invoice->attachment_path)
                <p class="text-sm text-muted">La nueva foto sustituye al adjunto actual.</p>
            @endif

            <p class="text-sm font-medium text-danger" data-capture-error hidden></p>
            @error('attachment')
                <p class="text-sm font-medium text-danger">{{ $message }}</p>
            @enderror
        @endif
    </section>

    <div class="space-y-3">
        @if ($invoice->status !== 'paid')
            <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-primary w-full">Marcar como pagada</button>
            </form>
        @endif

        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn">Editar</a>
            <button type="button" class="btn text-danger" data-open-dialog="confirm-delete">Eliminar</button>
        </div>
    </div>

    <dialog id="confirm-delete" class="card m-auto w-[calc(100%-2rem)] max-w-sm p-5 text-ink">
        <h2 class="text-lg font-semibold">¿Eliminar la factura {{ $invoice->number }}?</h2>
        <p class="mt-1 text-sm text-muted">También se borra su adjunto. No se puede deshacer.</p>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <form method="dialog">
                <button class="btn w-full">Cancelar</button>
            </form>
            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">Eliminar</button>
            </form>
        </div>
    </dialog>
@endsection
