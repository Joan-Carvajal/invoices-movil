@extends('layouts.guest')

@section('title', 'Entrar')

@section('content')
    <h1 class="mb-5 text-2xl font-bold tracking-tight">Entrar</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-field name="email" label="Email">
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" autocomplete="username" autocapitalize="none" inputmode="email" @error('email') aria-invalid="true" @enderror required autofocus>
        </x-field>

        <x-field name="password" label="Contraseña">
            <input id="password" class="input" type="password" name="password" autocomplete="current-password" required>
        </x-field>

        <label class="flex min-h-11 items-center gap-3 text-sm">
            <input type="checkbox" name="remember" class="size-5 accent-brand" @checked(old('remember', \App\Support\NativeApp::running()))>
            Recordarme
        </label>

        <button type="submit" class="btn btn-primary w-full">Entrar</button>
    </form>
@endsection
