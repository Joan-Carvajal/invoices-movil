<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f4f5f7" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0e1115" media="(prefers-color-scheme: dark)">
    <title>@yield('title') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-canvas font-sans text-ink antialiased">
    <main class="safe-page mx-auto max-w-sm px-4">
        <p class="mb-8 text-center text-lg font-semibold">{{ config('app.name') }}</p>

        @yield('content')
    </main>
</body>
</html>
