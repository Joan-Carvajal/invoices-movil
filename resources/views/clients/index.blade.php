@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <h1>Clientes</h1>

    <p><a href="{{ route('clients.create') }}">Nuevo cliente</a></p>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>NIF</th>
                <th>Facturas</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->tax_id }}</td>
                    <td>{{ $client->invoices_count }}</td>
                    <td><a href="{{ route('clients.edit', $client) }}">Editar</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Todavía no tienes clientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection