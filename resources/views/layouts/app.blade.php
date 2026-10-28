<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 960px; margin: 2rem auto; padding: 0 1rem; color: #1f2937; }
        nav { display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; }
        nav form { margin-left: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .5rem; border-bottom: 1px solid #e5e7eb; }
        label { display: block; margin-top: 1rem; }
        .status { background: #ecfdf5; padding: .75rem; }
        .errors { color: #b91c1c; }
    </style>
</head>
<body>
    @auth
        <nav>
            <a href="{{ route('invoices.index') }}">Facturas</a>
            <a href="{{ route('clients.index') }}">Clientes</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                {{ auth()->user()->name }}
                <button type="submit">Salir</button>
            </form>
        </nav>
    @endauth

    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @yield('content')
</body>
</html>