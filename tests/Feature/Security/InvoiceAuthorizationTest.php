<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('local');

    test()->ana = userWithInvoice();
    test()->luis = userWithInvoice();
});

it('forbids the invoice of another user', function () {
    actingAs(test()->ana)
        ->get(route('invoices.show', invoiceOf(test()->luis)))
        ->assertForbidden();
});

it('forbids the attachment of another user', function () {
    actingAs(test()->ana)
        ->get(route('invoices.attachment', invoiceOf(test()->luis)))
        ->assertForbidden();
});
