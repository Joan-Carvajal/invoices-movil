<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('lists only the clients of the user', function () {
    $user = User::factory()->create();
    Client::factory()->for($user)->create(['name' => 'Talleres Ruiz']);
    Client::factory()->create(['name' => 'Otra Empresa']);

    actingAs($user)
        ->get(route('clients.index'))
        ->assertOk()
        ->assertSee('Talleres Ruiz')
        ->assertDontSee('Otra Empresa');
});

it('creates a client for the user', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('clients.store'), [
            'name' => 'Talleres Ruiz',
            'email' => 'hola@talleresruiz.test',
            'tax_id' => 'B12345678',
        ])
        ->assertRedirect(route('clients.index'));

    expect($user->clients()->first())
        ->name->toBe('Talleres Ruiz')
        ->tax_id->toBe('B12345678');
});

it('requires a name to create a client', function () {
    actingAs(User::factory()->create())
        ->post(route('clients.store'), ['name' => ''])
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio.']);
});

it('updates a client', function () {
    $client = Client::factory()->create();

    actingAs($client->user)
        ->put(route('clients.update', $client), [
            'name' => 'Talleres Ruiz e Hijos',
            'email' => 'hola@talleresruiz.test',
            'tax_id' => 'B12345678',
        ])
        ->assertRedirect(route('clients.index'));

    expect($client->fresh()->name)->toBe('Talleres Ruiz e Hijos');
});

it('does not let a user edit a client of another user', function () {
    $client = Client::factory()->create();

    actingAs(User::factory()->create())
        ->get(route('clients.edit', $client))
        ->assertForbidden();
});

it('does not let a client be reassigned to another user', function () {
    $client = Client::factory()->create();
    $owner = $client->user;

    actingAs($owner)
        ->put(route('clients.update', $client), [
            'name' => 'Talleres Ruiz',
            'user_id' => User::factory()->create()->id,
        ])
        ->assertRedirect(route('clients.index'));

    expect($client->fresh()->user_id)->toBe($owner->id);
});
