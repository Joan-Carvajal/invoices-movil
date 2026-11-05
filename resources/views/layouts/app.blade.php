<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f4f5f7" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0e1115" media="(prefers-color-scheme: dark)">
    <title>@yield('title') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-canvas font-sans text-ink antialiased">
    @auth
        <header class="safe-header sticky top-0 z-10 border-b border-line bg-surface/95 backdrop-blur">
            <div class="mx-auto flex h-14 max-w-3xl items-center gap-6 px-4">
                <a href="{{ route('invoices.index') }}" class="hidden font-semibold md:block">{{ config('app.name') }}</a>

                <nav class="hidden gap-1 md:flex" aria-label="Principal">
                    <a href="{{ route('invoices.index') }}" class="btn btn-quiet" @if (request()->routeIs('invoices.*')) aria-current="page" @endif>Facturas</a>
                    <a href="{{ route('clients.index') }}" class="btn btn-quiet" @if (request()->routeIs('clients.*')) aria-current="page" @endif>Clientes</a>
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="flex min-w-0 flex-1 items-center justify-between gap-2 md:justify-end">
                    @csrf
                    <span class="truncate text-sm text-muted">{{ auth()->user()->name }}</span>
                    <button type="submit" class="btn btn-quiet">Salir</button>
                </form>
            </div>
        </header>
    @endauth

    <main class="safe-main mx-auto max-w-3xl px-4 pt-5 md:pb-10">
        @if (session('status'))
            <p role="status" class="mb-4 rounded-xl bg-success-soft px-4 py-3 text-sm font-medium text-success">{{ session('status') }}</p>
        @endif

        @yield('content')
    </main>

    @auth
        <nav class="safe-tabbar fixed inset-x-0 bottom-0 z-10 border-t border-line bg-surface md:hidden" aria-label="Principal">
            <div class="grid h-16 grid-cols-2">
                <x-tab :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" label="Facturas">
                    <path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>
                </x-tab>
                <x-tab :href="route('clients.index')" :active="request()->routeIs('clients.*')" label="Clientes">
                    <path d="M16 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1"/><circle cx="9.5" cy="8" r="3.5"/><path d="M21 20v-1a4 4 0 0 0-3-3.87M15.5 4.6a3.5 3.5 0 0 1 0 6.8"/>
                </x-tab>
            </div>
        </nav>
    @endauth
</body>
</html>
