<?php

namespace App\Services;

use App\Models\Club;
use App\Models\Player;
use App\Models\Contract;
use Carbon\Carbon;

class PlayerGenerationService
{
    public function generatePlayersForClub(Club $club, int $count = 22)
    {
        $positions = [
            'GK' => 3,
            'DEF' => 7,
            'MID' => 7,
            'FWD' => 5
        ];

        foreach ($positions as $position => $positionCount) {
            for ($i = 0; $i < $positionCount; $i++) {
                $player = $this->createPlayer($club, $position);
                $this->createInitialContract($player, $club);
            }
        }
    }

    private function createPlayer(Club $club, string $position): Player
    {
        $player = Player::create([
            'first_name' => $this->generateFirstName(),
            'last_name' => $this->generateLastName(),
            'club_id' => $club->id,
            'birth_date' => $this->generateBirthDate(),
            'position' => $position,
            'strength' => rand(40, 80),
            'stamina' => rand(40, 80),
            'speed' => rand(40, 80),
            'technique' => rand(40, 80),
            'passing' => rand(40, 80),
            'goalkeeper' => $position === 'GK' ? rand(60, 85) : rand(20, 40),
            'defense' => $position === 'DEF' ? rand(60, 85) : rand(30, 50),
            'midfield' => $position === 'MID' ? rand(60, 85) : rand(30, 50),
            'striker' => $position === 'FWD' ? rand(60, 85) : rand(30, 50),
        ]);

        return $player;
    }

    private function createInitialContract(Player $player, Club $club): Contract
    {
        return Contract::create([
            'player_id' => $player->id,
            'club_id' => $club->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addYears(rand(2, 4)),
            'salary' => $this->calculateInitialSalary($player),
            'active' => true
        ]);
    }

    private function calculateInitialSalary(Player $player): int
    {
        $baseSalary = 1000;
        $skillAverage = ($player->strength + $player->stamina + $player->speed +
                        $player->technique + $player->passing) / 5;

        return (int) ($baseSalary * ($skillAverage / 50));
    }

    private function generateFirstName(): string
    {
        // Implementera med en lista av förnamn
        $names = ['Erik', 'Johan', 'Karl', 'Anders', 'Lars', 'Per', 'Nils'];
        return $names[array_rand($names)];
    }

    private function generateLastName(): string
    {
        // Implementera med en lista av efternamn
        $names = ['Andersson', 'Johansson', 'Karlsson', 'Nilsson', 'Eriksson'];
        return $names[array_rand($names)];
    }

    private function generateBirthDate(): Carbon
    {
        return Carbon::now()->subYears(rand(18, 35))->subDays(rand(0, 365));
    }
}
