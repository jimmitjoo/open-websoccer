<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeagueClubStatisticFactory extends Factory
{
    public function definition(): array
    {
        return [
            'matches_played' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'clean_sheets' => 0,
            'failed_to_score' => 0,
            'points' => 0,
        ];
    }
}
