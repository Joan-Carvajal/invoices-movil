@extends('layouts.guest')

@section('title', 'Crea tu cuenta')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight">Crea tu cuenta</h1>
    <p class="mt-2 mb-5 text-sm text-muted">Tus clientes y facturas se guardan solo en este dispositivo. Si olvidas la contraseña no se puede recuperar.</p>

    <form method="POST" action="{{ route('setup') }}" class="space-y-4">
        @csrf

        <x-field name="name" label="Nombre">
            <input id="name" class="input" name="name" value="{{ old('name') }}" autocomplete="name" @error('name') aria-invalid="true" @enderror required autofocus>
        </x-field>

        <x-field name="email" label="Email">
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" autocomplete="username" autocapitalize="none" inputmode="email" @error('email') aria-invalid="true" @enderror required>
        </x-field>

        <x-field name="password" label="Contraseña" hint="Al menos 8 caracteres.">
            <input id="password" class="input" type="password" name="password" autocomplete="new-password" minlength="8" @error('password') aria-invalid="true" @enderror required>
        </x-field>

        <x-field name="password_confirmation" label="Repite la contraseña">
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        </x-field>

        <button type="submit" class="btn btn-primary w-full">Empezar</button>
    </form>
@endsection
