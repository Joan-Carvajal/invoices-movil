<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Support\NativeApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Native\Mobile\Facades\Share;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceAttachmentController extends Controller
{
    public function __invoke(Invoice $invoice): StreamedResponse|RedirectResponse
    {
        Gate::authorize('view', $invoice);
        abort_if($invoice->attachment_path === null, 404);

        if (NativeApp::running()) {
            Share::file($invoice->attachment_name, '', $this->copyForSharing($invoice));

            return redirect()->route('invoices.show', $invoice);
        }

        return Storage::disk('local')->download($invoice->attachment_path, $invoice->attachment_name);
    }

    /**
     * La WebView no gestiona descargas, así que el adjunto sale por el menú de
     * compartir, con su nombre original en lugar del nombre interno del disco.
     */
    private function copyForSharing(Invoice $invoice): string
    {
        $directory = config('nativephp-internal.tempdir').'/attachments';
        File::ensureDirectoryExists($directory);

        $copy = $directory.'/'.basename($invoice->attachment_name);
        File::copy(Storage::disk('local')->path($invoice->attachment_path), $copy);

        return $copy;
    }
}
