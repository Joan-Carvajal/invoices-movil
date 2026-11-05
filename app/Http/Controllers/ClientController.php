<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = $request->user()->clients()
            ->withCount('invoices')
            ->orderBy('name')
            ->get();

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.form', ['client' => new Client]);
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $request->user()->clients()->create($request->validated());

        return redirect()->route('clients.index')->with('status', 'Cliente creado.');
    }

    public function edit(Request $request, Client $client): View
    {
        abort_unless($client->user()->is($request->user()), 403);

        return view('clients.form', compact('client'));
    }

    public function update(StoreClientRequest $request, Client $client): RedirectResponse
    {
        abort_unless($client->user()->is($request->user()), 403);

        $client->update($request->validated());

        return redirect()->route('clients.index')->with('status', 'Cliente actualizado.');
    }
}
