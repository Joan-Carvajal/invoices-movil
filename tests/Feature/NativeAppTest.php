<?php

declare(strict_types=1);

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Native\Mobile\Facades\Share;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

/**
 * Una imagen en el directorio donde la deja la cámara del dispositivo.
 */
function capturedPhoto(string $name = 'captured_1.jpg', string $directory = 'Camera'): string
{
    $path = sys_get_temp_dir().'/'.uniqid('native_').'/'.$directory.'/'.$name;
    File::ensureDirectoryExists(dirname($path));

    $image = imagecreatetruecolor(10, 10);
    imagejpeg($image, $path);

    return $path;
}

beforeEach(function () {
    Storage::fake('local');
});

describe('off the device', function () {
    it('does not expose the native bridge', function (string $uri) {
        post($uri)->assertNotFound();
    })->with(['_native/api/call', '_native/api/events']);

    it('does not offer the first run setup', function () {
        get(route('setup'))->assertNotFound();

        post(route('setup'), [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secreto-largo',
            'password_confirmation' => 'secreto-largo',
        ])->assertNotFound();

        expect(User::count())->toBe(0);
    });

    it('does not accept device photos', function () {
        $invoice = Invoice::factory()->create();

        actingAs($invoice->client->user)
            ->post(route('invoices.photo', $invoice), ['path' => capturedPhoto()])
            ->assertNotFound();
    });

    it('still downloads the attachment', function () {
        $invoice = Invoice::factory()->withAttachment()->create();

        actingAs($invoice->client->user)
            ->get(route('invoices.attachment', $invoice))
            ->assertDownload('factura.pdf');
    });
});

describe('on the device', function () {
    beforeEach(function () {
        config([
            'nativephp-internal.running' => true,
            'nativephp-internal.tempdir' => sys_get_temp_dir().'/'.uniqid('native_'),
        ]);
    });

    it('sends the first launch to the setup screen', function () {
        get(route('login'))->assertRedirect(route('setup'));
        get(route('setup'))->assertOk();
    });

    it('creates the account of the device and signs in', function () {
        post(route('setup'), [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secreto-largo',
            'password_confirmation' => 'secreto-largo',
        ])->assertRedirect(route('invoices.index'));

        assertAuthenticated();
        expect(User::sole()->email)->toBe('ana@example.com');
    });

    it('validates the account of the device', function () {
        post(route('setup'), [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'corta',
            'password_confirmation' => 'corta',
        ])->assertSessionHasErrors('password');

        assertGuest();
        expect(User::count())->toBe(0);
    });

    it('closes the setup once the account exists', function () {
        User::factory()->create();

        get(route('setup'))->assertNotFound();
        post(route('setup'), [
            'name' => 'Intruso',
            'email' => 'intruso@example.com',
            'password' => 'secreto-largo',
            'password_confirmation' => 'secreto-largo',
        ])->assertNotFound();

        get(route('login'))->assertOk();
        expect(User::count())->toBe(1);
    });

    it('shares the attachment with its original name', function () {
        $invoice = Invoice::factory()->withAttachment()->create();

        Share::shouldReceive('file')
            ->once()
            ->withArgs(fn (string $title, string $text, string $path) => $title === 'factura.pdf'
                && basename($path) === 'factura.pdf'
                && str_starts_with($path, config('nativephp-internal.tempdir'))
                && file_get_contents($path) === 'PDF de prueba');

        actingAs($invoice->client->user)
            ->get(route('invoices.attachment', $invoice))
            ->assertRedirect(route('invoices.show', $invoice));
    });

    it('attaches a photo, replaces the previous attachment and removes the capture', function () {
        $invoice = Invoice::factory()->withAttachment()->create(['number' => 'F/2026/7']);
        $previous = $invoice->attachment_path;
        $photo = capturedPhoto();

        actingAs($invoice->client->user)
            ->post(route('invoices.photo', $invoice), ['path' => $photo])
            ->assertRedirect(route('invoices.show', $invoice));

        $invoice->refresh();

        expect($invoice->attachment_name)->toBe('f-2026-7.jpg')
            ->and(file_exists($photo))->toBeFalse();
        Storage::disk('local')->assertExists($invoice->attachment_path);
        Storage::disk('local')->assertMissing($previous);
    });

    it('accepts a gallery image without extension', function () {
        $invoice = Invoice::factory()->create(['number' => 'F-1']);

        actingAs($invoice->client->user)
            ->post(route('invoices.photo', $invoice), ['path' => capturedPhoto('gallery_selected_1', 'Gallery')])
            ->assertSessionHasNoErrors();

        expect($invoice->fresh()->attachment_name)->toBe('f-1.jpg');
    });

    it('refuses a path outside the camera and gallery directories', function (string $path) {
        $invoice = Invoice::factory()->create();

        actingAs($invoice->client->user)
            ->post(route('invoices.photo', $invoice), ['path' => $path])
            ->assertUnprocessable();

        expect($invoice->fresh()->attachment_path)->toBeNull();
    })->with([
        'application file' => fn () => base_path('.env.example'),
        'missing file' => '/no/existe/Camera/foto.jpg',
        'traversal' => fn () => dirname(capturedPhoto()).'/../../../../'.base_path('composer.json'),
    ]);

    it('refuses a file that is not an image and keeps it', function () {
        $invoice = Invoice::factory()->create();
        $path = capturedPhoto('nota.txt');
        file_put_contents($path, 'texto');

        actingAs($invoice->client->user)
            ->post(route('invoices.photo', $invoice), ['path' => $path])
            ->assertSessionHasErrors('attachment');

        expect($invoice->fresh()->attachment_path)->toBeNull()
            ->and(file_exists($path))->toBeTrue();
    });

    it('does not let a user attach a photo to an invoice of another user', function () {
        $invoice = Invoice::factory()->create();

        actingAs(User::factory()->create())
            ->post(route('invoices.photo', $invoice), ['path' => capturedPhoto()])
            ->assertForbidden();
    });
});
