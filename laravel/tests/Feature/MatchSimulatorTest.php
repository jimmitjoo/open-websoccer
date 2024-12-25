<?php

use App\Models\Player;
use App\Models\Game;
use App\Models\Club;
use App\Models\Injury;
use App\Services\MatchSimulator;
use App\Services\InjuryService;
use Illuminate\Support\Facades\DB;
use App\Models\LeagueClubStatistic;
use App\Enums\GameStatus;

beforeEach(function () {
    $this->seed(\Database\Seeders\InjuryTypeSeeder::class);
});

it('creates injuries during a match', function () {
    // Create two clubs with players
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    // Create a match
    $match = Game::factory()->create([
        'home_club_id' => $homeClub->id,
        'away_club_id' => $awayClub->id,
        'status' => 'scheduled'
    ]);

    // Simulate the match multiple times to ensure injuries are created
    $simulator = app(MatchSimulator::class);
    $totalInjuries = 0;

    // Run the simulation 50 times to get a good statistical basis
    for ($i = 0; $i < 50; $i++) {
        $match->refresh();
        $match->status = 'scheduled';
        $match->save();

        $simulator->simulate($match);

        $injuries = Injury::where('match_id', $match->id)->count();
        $totalInjuries += $injuries;
    }

    // With the current probability (0.5% per player per minute)
    // and ~30 players per match over 90 minutes
    // we expect about 13.5 injuries per match (0.005 * 30 * 90)
    // Over 50 matches we should see at least some injuries
    expect($totalInjuries)->toBeGreaterThan(0);

    // Check that the injuries are correctly recorded
    $injury = Injury::where('match_id', $match->id)->first();
    if ($injury) {
        expect($injury)
            ->started_at->not->toBeNull()
            ->expected_return_at->toBeGreaterThan($injury->started_at)
            ->actual_return_at->toBeNull();

        // Check that the injury is linked to a player from one of the clubs
        expect([$homeClub->id, $awayClub->id])
            ->toContain($injury->player->club_id);
    }
});

it('increases injury risk for tired players', function () {
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    // Create a tired player
    $tiredPlayer = Player::factory()->create([
        'club_id' => $homeClub->id,
        'stamina' => 1
    ]);

    // Create a fresh player
    $freshPlayer = Player::factory()->create([
        'club_id' => $homeClub->id,
        'stamina' => 99
    ]);

    $match = Game::factory()->create([
        'home_club_id' => $homeClub->id,
        'away_club_id' => $awayClub->id
    ]);

    $simulator = app(MatchSimulator::class);

    $tiredPlayerInjuries = 0;
    $freshPlayerInjuries = 0;

    // Simulate many matches to see patterns
    for ($i = 0; $i < 30; $i++) {
        $match->refresh();
        $match->status = 'scheduled';
        $match->save();

        $simulator->simulate($match);

        $tiredPlayerInjuries += Injury::where('player_id', $tiredPlayer->id)->count();
        $freshPlayerInjuries += Injury::where('player_id', $freshPlayer->id)->count();
    }

    // The tired player should have more injuries
    expect($tiredPlayerInjuries)->toBeGreaterThan($freshPlayerInjuries);
});

it('saves match results in the database', function () {
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    $match = Game::factory()->create([
        'home_club_id' => $homeClub->id,
        'away_club_id' => $awayClub->id,
        'status' => 'scheduled',
        'home_score' => null,
        'away_score' => null
    ]);

    // Create initial statistics for both clubs
    LeagueClubStatistic::factory()->create([
        'club_id' => $homeClub->id,
        'league_id' => $match->league_id,
        'season_id' => $match->season_id,
    ]);

    LeagueClubStatistic::factory()->create([
        'club_id' => $awayClub->id,
        'league_id' => $match->league_id,
        'season_id' => $match->season_id,
    ]);

    $simulator = app(MatchSimulator::class);
    $simulator->simulate($match);

    $match->refresh();

    expect($match)
        ->status->toBe(GameStatus::COMPLETED)
        ->home_score->not->toBeNull()
        ->away_score->not->toBeNull();

    // Verify that the league statistics were updated
    $homeStats = DB::table('league_club_statistics')
        ->where('club_id', $homeClub->id)
        ->where('league_id', $match->league_id)
        ->where('season_id', $match->season_id)
        ->first();

    expect($homeStats->matches_played)->toBe(1);
});

it('can simulate multiple matches in a row', function () {
    // Create matches for today
    $today = now()->format('Y-m-d');
    $matches = Game::factory(5)->create([
        'status' => 'scheduled',
        'scheduled_at' => now()->subMinutes(10)
    ]);

    // Create statistics for all clubs in the matches
    foreach ($matches as $match) {
        $homeData = [
            'club_id' => $match->home_club_id,
            'league_id' => $match->league_id,
            'season_id' => $match->season_id,
        ];

        $awayData = [
            'club_id' => $match->away_club_id,
            'league_id' => $match->league_id,
            'season_id' => $match->season_id,
        ];

        if (!LeagueClubStatistic::where($homeData)->exists()) {
            LeagueClubStatistic::factory()->create($homeData);
        }

        if (!LeagueClubStatistic::where($awayData)->exists()) {
            LeagueClubStatistic::factory()->create($awayData);
        }
    }

    // Run the command with today's date
    $this->artisan('matches:simulate', ['--date' => $today])
        ->assertSuccessful();

    foreach ($matches as $match) {
        expect(Game::find($match->id))
            ->status->toBe(GameStatus::COMPLETED)
            ->home_score->not->toBeNull()
            ->away_score->not->toBeNull();
    }
});
