<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Support\NativeApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoicePhotoController extends Controller
{
    /**
     * Adjunta una foto que la cámara o la galería han dejado en el dispositivo.
     */
    public function __invoke(Request $request, Invoice $invoice): RedirectResponse
    {
        abort_unless(NativeApp::running(), 404);
        Gate::authorize('update', $invoice);

        $path = realpath($request->string('path')->toString());
        abort_unless($path !== false && preg_match('#/(Camera|Gallery)/[^/]+$#', $path) === 1, 422);

        $photo = new UploadedFile($path, basename($path), test: true);

        Validator::make(['attachment' => $photo], [
            'attachment' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ])->validate();

        $invoice->attachFile($photo, Str::of($invoice->number)->replace('/', '-')->slug().'.'.$photo->guessExtension());
        $invoice->save();
        File::delete($path);

        return redirect()->route('invoices.show', $invoice)->with('status', 'Foto adjuntada.');
    }
}
