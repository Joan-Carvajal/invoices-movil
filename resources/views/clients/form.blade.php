@extends('layouts.app')

@section('title', $client->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
    <x-page-header :title="$client->exists ? 'Editar cliente' : 'Nuevo cliente'" :back="route('clients.index')" />

    <form method="POST" action="{{ $client->exists ? route('clients.update', $client) : route('clients.store') }}" class="card space-y-4 p-4">
        @csrf
        @if ($client->exists)
            @method('PUT')
        @endif

        <x-field name="name" label="Nombre">
            <input id="name" class="input" name="name" value="{{ old('name', $client->name) }}" autocomplete="organization" @error('name') aria-invalid="true" @enderror required>
        </x-field>

        <x-field name="email" label="Email">
            <input id="email" class="input" type="email" name="email" value="{{ old('email', $client->email) }}" autocomplete="email" autocapitalize="none" inputmode="email" @error('email') aria-invalid="true" @enderror>
        </x-field>

        <x-field name="tax_id" label="NIF">
            <input id="tax_id" class="input" name="tax_id" value="{{ old('tax_id', $client->tax_id) }}" autocapitalize="characters" autocomplete="off" @error('tax_id') aria-invalid="true" @enderror>
        </x-field>

        <button type="submit" class="btn btn-primary w-full">Guardar</button>
    </form>
@endsection
