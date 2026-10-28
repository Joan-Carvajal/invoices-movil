<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
});

it('lists the invoices of the user and searches by number', function () {
    $client = Client::factory()->create();
    Invoice::factory()->for($client)->create(['number' => 'F-2026-0001']);
    Invoice::factory()->for($client)->create(['number' => 'F-2026-0002']);

    actingAs($client->user)
        ->get(route('invoices.index', ['q' => 'f-2026-0001']))
        ->assertOk()
        ->assertSee('F-2026-0001')
        ->assertDontSee('F-2026-0002');
});

it('creates an invoice with an attachment', function () {
    $client = Client::factory()->create();

    actingAs($client->user)
        ->post(route('invoices.store'), [
            'client_id' => $client->id,
            'number' => 'F-2026-0100',
            'amount' => '1250.00',
            'status' => 'sent',
            'issued_at' => '2026-09-01',
            'notes' => "Pago a 30 días.\nTransferencia bancaria.",
            'attachment' => UploadedFile::fake()->create('factura.pdf', 120, 'application/pdf'),
        ])
        ->assertRedirect();

    $invoice = Invoice::firstWhere('number', 'F-2026-0100');

    expect($invoice->attachment_name)->toBe('factura.pdf');
    Storage::disk('local')->assertExists($invoice->attachment_path);
});

it('does not create an invoice for a client of another user', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('invoices.store'), [
            'client_id' => Client::factory()->create()->id,
            'number' => 'F-2026-0100',
            'amount' => '1250.00',
            'status' => 'sent',
            'issued_at' => '2026-09-01',
        ])
        ->assertSessionHasErrors('client_id');
});

it('shows an invoice with its notes', function () {
    $invoice = Invoice::factory()->create(['notes' => 'Pago a 30 días.']);

    actingAs($invoice->client->user)
        ->get(route('invoices.show', $invoice))
        ->assertOk()
        ->assertSee($invoice->number)
        ->assertSee('Pago a 30 días.');
});

it('downloads the attachment of an invoice', function () {
    $invoice = Invoice::factory()->withAttachment()->create();

    actingAs($invoice->client->user)
        ->get(route('invoices.attachment', $invoice))
        ->assertOk()
        ->assertDownload('factura.pdf');
});

it('updates an invoice', function () {
    $invoice = Invoice::factory()->create(['status' => 'draft']);

    actingAs($invoice->client->user)
        ->put(route('invoices.update', $invoice), [
            'client_id' => $invoice->client_id,
            'number' => $invoice->number,
            'amount' => '99.90',
            'status' => 'paid',
            'issued_at' => '2026-09-01',
        ])
        ->assertRedirect(route('invoices.show', $invoice));

    expect($invoice->fresh()->status)->toBe('paid');
});

it('deletes an invoice and its attachment', function () {
    $invoice = Invoice::factory()->withAttachment()->create();

    actingAs($invoice->client->user)
        ->delete(route('invoices.destroy', $invoice))
        ->assertRedirect(route('invoices.index'));

    expect(Invoice::find($invoice->id))->toBeNull();
    Storage::disk('local')->assertMissing($invoice->attachment_path);
});

it('does not let a user update or delete an invoice of another user', function () {
    $invoice = Invoice::factory()->create();
    $other = User::factory()->create();

    actingAs($other)
        ->put(route('invoices.update', $invoice), [
            'client_id' => $invoice->client_id,
            'number' => $invoice->number,
            'amount' => '1.00',
            'status' => 'paid',
            'issued_at' => '2026-09-01',
        ])
        ->assertForbidden();

    actingAs($other)
        ->delete(route('invoices.destroy', $invoice))
        ->assertForbidden();
});
