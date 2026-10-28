<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceAttachmentController extends Controller
{
    public function __invoke(Invoice $invoice): StreamedResponse
    {
        abort_if($invoice->attachment_path === null, 404);

        return Storage::disk('local')->download($invoice->attachment_path, $invoice->attachment_name);
    }
}
