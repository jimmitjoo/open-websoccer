<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\League;
use App\Models\Club;
use App\Models\Stadium;
use App\Models\Season;
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

        // Skapa säsong
        $season = Season::create([
            'name' => '2024/25',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
            'is_active' => true,
        ]);

        // Skapa svenska ligor
        $allsvenskan = League::create([
            'name' => 'Allsvenskan',
            'country_code' => 'SE',
            'level' => 'national',
            'rank' => 1,
            'max_teams' => 16,
        ]);

        $superettan = League::create([
            'name' => 'Superettan',
            'country_code' => 'SE',
            'level' => 'national',
            'rank' => 2,
            'max_teams' => 16,
        ]);

        // Skapa några klubbar för Allsvenskan
        $allsvenskanClubs = [
            ['name' => 'Malmö FF', 'short' => 'MFF', 'stadium' => 'Eleda Stadion', 'capacity' => 24000],
            ['name' => 'AIK', 'short' => 'AIK', 'stadium' => 'Friends Arena', 'capacity' => 50000],
            ['name' => 'IFK Göteborg', 'short' => 'IFK', 'stadium' => 'Gamla Ullevi', 'capacity' => 18800],
            ['name' => 'Djurgårdens IF', 'short' => 'DIF', 'stadium' => 'Tele2 Arena', 'capacity' => 30000],
            ['name' => 'Hammarby IF', 'short' => 'HIF', 'stadium' => 'Tele2 Arena', 'capacity' => 30000],
        ];

        foreach ($allsvenskanClubs as $clubData) {
            $stadium = Stadium::create([
                'name' => $clubData['stadium'],
                'capacity_seats' => (int)($clubData['capacity'] * 0.7),
                'capacity_stands' => (int)($clubData['capacity'] * 0.2),
                'capacity_vip' => (int)($clubData['capacity'] * 0.1),
                'level_pitch' => 5,
                'level_seats' => 4,
                'level_stands' => 4,
                'level_vip' => 4,
                'price_seats' => 250,
                'price_stands' => 150,
                'price_vip' => 1000,
            ]);

            $club = Club::create([
                'name' => $clubData['name'],
                'short_name' => $clubData['short'],
                'stadium_id' => $stadium->id,
                'is_active' => true,
            ]);

            // Koppla klubben till Allsvenskan med statistik
            $club->leagues()->attach($allsvenskan->id, [
                'season_id' => $season->id,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'current_position' => 0,
            ]);
        }

        // Skapa några klubbar för Superettan
        $superettanClubs = [
            ['name' => 'Östers IF', 'short' => 'ÖIF', 'stadium' => 'Visma Arena', 'capacity' => 12000],
            ['name' => 'GAIS', 'short' => 'GAIS', 'stadium' => 'Gamla Ullevi', 'capacity' => 18800],
            ['name' => 'Örgryte IS', 'short' => 'ÖIS', 'stadium' => 'Gamla Ullevi', 'capacity' => 18800],
        ];

        foreach ($superettanClubs as $clubData) {
            $stadium = Stadium::create([
                'name' => $clubData['stadium'],
                'capacity_seats' => (int)($clubData['capacity'] * 0.6),
                'capacity_stands' => (int)($clubData['capacity'] * 0.3),
                'capacity_vip' => (int)($clubData['capacity'] * 0.1),
                'level_pitch' => 4,
                'level_seats' => 3,
                'level_stands' => 3,
                'level_vip' => 3,
                'price_seats' => 200,
                'price_stands' => 120,
                'price_vip' => 800,
            ]);

            $club = Club::create([
                'name' => $clubData['name'],
                'short_name' => $clubData['short'],
                'stadium_id' => $stadium->id,
                'is_active' => true,
            ]);

            // Koppla klubben till Superettan med statistik
            $club->leagues()->attach($superettan->id, [
                'season_id' => $season->id,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'current_position' => 0,
            ]);
        }

        // Koppla ihop ligor med säsongen
        $allsvenskan->seasons()->attach($season->id, [
            'start_date' => '2024-04-01',
            'end_date' => '2024-11-30',
        ]);

        $superettan->seasons()->attach($season->id, [
            'start_date' => '2024-04-01',
            'end_date' => '2024-11-30',
        ]);
    }
}
