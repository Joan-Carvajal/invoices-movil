<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            ['name' => 'Ana García', 'email' => 'ana@example.com'],
            ['name' => 'Luis Martín', 'email' => 'luis@example.com'],
        ];

        foreach ($users as $user) {
            User::factory()
                ->has(Client::factory()->count(3)->has(Invoice::factory()->count(4)->withAttachment()))
                ->create($user);
        }
    }
}
