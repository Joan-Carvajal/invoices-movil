<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'number' => fake()->unique()->numerify('F-2026-####'),
            'amount' => fake()->randomFloat(2, 50, 5000),
            'status' => fake()->randomElement(array_keys(Invoice::STATUSES)),
            'issued_at' => fake()->dateTimeBetween('-3 months'),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    public function withAttachment(): static
    {
        return $this->state(function (): array {
            $path = 'attachments/'.Str::uuid().'.pdf';
            Storage::disk('local')->put($path, 'PDF de prueba');

            return [
                'attachment_path' => $path,
                'attachment_name' => 'factura.pdf',
            ];
        });
    }
}
