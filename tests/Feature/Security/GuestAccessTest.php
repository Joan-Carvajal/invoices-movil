<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;

beforeEach(function () {
    Storage::fake('local');
    test()->invoice = invoiceOf(userWithInvoice());
});

it('redirects a guest to the login page', function (string $url) {
    get($url)->assertRedirect(route('login'));
})->with([
    'invoice list' => fn () => route('invoices.index'),
    'invoice' => fn () => route('invoices.show', test()->invoice),
    'attachment' => fn () => route('invoices.attachment', test()->invoice),
    'client list' => fn () => route('clients.index'),
]);
