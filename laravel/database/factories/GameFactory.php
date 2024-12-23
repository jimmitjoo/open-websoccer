<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\League;
use App\Models\Season;
use App\Models\Club;
use App\Models\Stadium;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_id' => League::factory(),
            'season_id' => Season::factory(),
            'home_club_id' => Club::factory(),
            'away_club_id' => Club::factory(),
            'stadium_id' => Stadium::factory(),
            'matchday' => 1,
            'scheduled_at' => now(),
            'home_score' => 0,
            'away_score' => 0,
            'status' => 'scheduled',
            'type' => 'league',
        ];
    }
}
