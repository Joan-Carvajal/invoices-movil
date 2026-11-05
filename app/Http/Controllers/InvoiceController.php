<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $invoices = $request->user()->invoices()
            ->with('client')
            ->when($search !== '', fn ($query) => $query->whereLike('invoices.number', '%'.$search.'%'))
            ->latest('issued_at')
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'search'));
    }

    public function create(Request $request): View
    {
        return view('invoices.form', [
            'invoice' => new Invoice(['status' => 'draft', 'issued_at' => now()]),
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = new Invoice($request->safe()->except('attachment'));
        $this->storeAttachment($request, $invoice);
        $invoice->save();

        return redirect()->route('invoices.show', $invoice)->with('status', 'Factura creada.');
    }

    public function show(Invoice $invoice): View
    {
        Gate::authorize('view', $invoice);
        $invoice->load('client');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Request $request, Invoice $invoice): View
    {
        Gate::authorize('update', $invoice);

        return view('invoices.form', [
            'invoice' => $invoice,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->fill($request->safe()->except('attachment'));
        $this->storeAttachment($request, $invoice);
        $invoice->save();

        return redirect()->route('invoices.show', $invoice)->with('status', 'Factura actualizada.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        Gate::authorize('delete', $invoice);

        if ($invoice->attachment_path !== null) {
            Storage::disk('local')->delete($invoice->attachment_path);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('status', 'Factura eliminada.');
    }

    private function storeAttachment(Request $request, Invoice $invoice): void
    {
        $file = $request->file('attachment');

        if (! $file instanceof UploadedFile) {
            return;
        }

        if ($invoice->attachment_path !== null) {
            Storage::disk('local')->delete($invoice->attachment_path);
        }

        $invoice->attachment_path = $file->store('attachments', 'local');
        $invoice->attachment_name = $file->getClientOriginalName();
    }
}
