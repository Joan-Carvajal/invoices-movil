@extends('layouts.app')

@section('title', $client->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
    <h1>{{ $client->exists ? 'Editar cliente' : 'Nuevo cliente' }}</h1>

    <form method="POST" action="{{ $client->exists ? route('clients.update', $client) : route('clients.store') }}">
        @csrf
        @if ($client->exists)
            @method('PUT')
        @endif

        <label for="name">Nombre</label>
        <input id="name" name="name" value="{{ old('name', $client->name) }}" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $client->email) }}">

        <label for="tax_id">NIF</label>
        <input id="tax_id" name="tax_id" value="{{ old('tax_id', $client->tax_id) }}">

        <p><button type="submit">Guardar</button></p>
    </form>
@endsection