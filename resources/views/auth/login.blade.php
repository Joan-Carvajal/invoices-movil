@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
    <h1>Entrar</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required>

        <label>
            <input type="checkbox" name="remember"> Recordarme
        </label>

        <p><button type="submit">Entrar</button></p>
    </form>
@endsection
