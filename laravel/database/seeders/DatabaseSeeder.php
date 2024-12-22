<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Skapa admin
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => Role::ADMIN,
            'language' => 'sv',
        ]);

        // Skapa managers
        $managers = [
            ['name' => 'Erik Larsson', 'email' => 'erik@example.com'],
            ['name' => 'Anna Nilsson', 'email' => 'anna@example.com'],
            ['name' => 'Johan Svensson', 'email' => 'johan@example.com'],
            ['name' => 'Maria Andersson', 'email' => 'maria@example.com'],
            ['name' => 'Karl Pettersson', 'email' => 'karl@example.com'],
            ['name' => 'Sofia Berg', 'email' => 'sofia@example.com'],
            ['name' => 'Anders Holm', 'email' => 'anders@example.com'],
            ['name' => 'Lisa Ekström', 'email' => 'lisa@example.com'],
            ['name' => 'Magnus Lindberg', 'email' => 'magnus@example.com'],
            ['name' => 'Eva Sjöberg', 'email' => 'eva@example.com'],
        ];

        foreach ($managers as $manager) {
            User::factory()->create([
                'name' => $manager['name'],
                'email' => $manager['email'],
                'password' => bcrypt('password'),
                'role' => Role::MANAGER,
                'language' => 'sv',
            ]);
        }

        // Skapa några vanliga användare också för variation
        User::factory(5)->create([
            'role' => Role::USER,
            'language' => 'sv',
        ]);
    }
}
