<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SetupController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/invoices');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('setup', [SetupController::class, 'create'])->name('setup');
    Route::post('setup', [SetupController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('clients', ClientController::class)->except(['show', 'destroy']);
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/attachment', InvoiceAttachmentController::class)->name('invoices.attachment');
    Route::patch('invoices/{invoice}/paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
});
