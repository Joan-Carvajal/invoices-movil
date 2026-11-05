<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-dvh bg-canvas font-sans text-ink antialiased">
    <main class="safe-page mx-auto max-w-sm px-4 text-center">
        <h1 class="text-2xl font-bold tracking-tight">@yield('title')</h1>
        <p class="mt-2 text-muted">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-6">Volver al inicio</a>
    </main>
</body>
</html>
