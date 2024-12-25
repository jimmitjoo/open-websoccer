<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Club;
use App\Models\League;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        // Skapa eller hämta en säsong med unikt datumintervall
        $startDate = $this->faker->dateTimeBetween('2024-01-01', '2026-12-31');
        $endDate = (clone $startDate)->modify('+3 months');

        $season = Season::firstOrCreate(
            [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            [
                'name' => $startDate->format('Y') . '-' . random_int(1, 4),
                'is_active' => true
            ]
        );

        // Skapa eller hämta en liga
        $league = League::factory()->create();

        // Skapa två olika klubbar
        $homeClub = Club::factory()->create();
        $awayClub = Club::factory()->create();

        // Skapa league_club_statistics för båda klubbarna
        DB::table('league_club_statistics')->insert([
            [
                'league_id' => $league->id,
                'club_id' => $homeClub->id,
                'season_id' => $season->id,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'current_position' => 0,
                'clean_sheets' => 0,
                'failed_to_score' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'league_id' => $league->id,
                'club_id' => $awayClub->id,
                'season_id' => $season->id,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'current_position' => 0,
                'clean_sheets' => 0,
                'failed_to_score' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return [
            'league_id' => $league->id,
            'season_id' => $season->id,
            'home_club_id' => $homeClub->id,
            'away_club_id' => $awayClub->id,
            'matchday' => $this->faker->numberBetween(1, 30),
            'scheduled_at' => now(),
            'home_score' => null,
            'away_score' => null,
            'type' => 'league',
            'status' => 'scheduled'
        ];
    }
}
