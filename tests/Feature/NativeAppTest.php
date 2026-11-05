<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

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

});

describe('on the device', function () {
    beforeEach(function () {
        config([
            'nativephp-internal.running' => true,
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

});
