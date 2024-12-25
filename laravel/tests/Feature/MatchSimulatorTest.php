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

it('skapar skador under en match', function () {
    // Skapa två klubbar med spelare
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    // Skapa en match
    $match = Game::factory()->create([
        'home_club_id' => $homeClub->id,
        'away_club_id' => $awayClub->id,
        'status' => 'scheduled'
    ]);

    // Simulera matchen flera gånger för att säkerställa att skador skapas
    $simulator = app(MatchSimulator::class);
    $totalInjuries = 0;

    // Kör simuleringen 50 gånger för att få ett bra statistiskt underlag
    for ($i = 0; $i < 50; $i++) {
        $match->refresh();
        $match->status = 'scheduled';
        $match->save();

        $simulator->simulate($match);

        $injuries = Injury::where('match_id', $match->id)->count();
        $totalInjuries += $injuries;
    }

    // Med nuvarande sannolikhet (0.5% per spelare per minut)
    // och ~30 spelare per match över 90 minuter
    // förväntar vi oss ungefär 13.5 skador per match (0.005 * 30 * 90)
    // Över 50 matcher bör vi se minst några skador
    expect($totalInjuries)->toBeGreaterThan(0);

    // Kontrollera att skadorna är korrekt registrerade
    $injury = Injury::where('match_id', $match->id)->first();
    if ($injury) {
        expect($injury)
            ->started_at->not->toBeNull()
            ->expected_return_at->toBeGreaterThan($injury->started_at)
            ->actual_return_at->toBeNull();

        // Verifiera att skadan är kopplad till en spelare från någon av klubbarna
        expect([$homeClub->id, $awayClub->id])
            ->toContain($injury->player->club_id);
    }
});

it('ökar skaderisken för trötta spelare', function () {
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    // Skapa en trött spelare
    $tiredPlayer = Player::factory()->create([
        'club_id' => $homeClub->id,
        'stamina' => 1
    ]);

    // Skapa en pigg spelare
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

    // Simulera många matcher för att se mönster
    for ($i = 0; $i < 30; $i++) {
        $match->refresh();
        $match->status = 'scheduled';
        $match->save();

        $simulator->simulate($match);

        $tiredPlayerInjuries += Injury::where('player_id', $tiredPlayer->id)->count();
        $freshPlayerInjuries += Injury::where('player_id', $freshPlayer->id)->count();
    }

    // Den trötta spelaren bör ha fler skador
    expect($tiredPlayerInjuries)->toBeGreaterThan($freshPlayerInjuries);
});

it('sparar matchresultat i databasen', function () {
    $homeClub = Club::factory()->create();
    $awayClub = Club::factory()->create();

    $match = Game::factory()->create([
        'home_club_id' => $homeClub->id,
        'away_club_id' => $awayClub->id,
        'status' => 'scheduled',
        'home_score' => null,
        'away_score' => null
    ]);

    // Skapa initiala statistikrader för båda lagen
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

    // Verifiera att ligastatistiken uppdaterades
    $homeStats = DB::table('league_club_statistics')
        ->where('club_id', $homeClub->id)
        ->where('league_id', $match->league_id)
        ->where('season_id', $match->season_id)
        ->first();

    expect($homeStats->matches_played)->toBe(1);
});

it('kan simulera flera matcher i följd', function () {
    // Skapa matcher för dagens datum
    $today = now()->format('Y-m-d');
    $matches = Game::factory(5)->create([
        'status' => 'scheduled',
        'scheduled_at' => now()->subMinutes(10)
    ]);

    // Skapa statistikrader för alla lag i matcherna
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

    // Kör kommandot med dagens datum
    $this->artisan('matches:simulate', ['--date' => $today])
        ->expectsOutput("Simulerar 5 matcher för {$today}")
        ->assertSuccessful();

    foreach ($matches as $match) {
        expect(Game::find($match->id))
            ->status->toBe(GameStatus::COMPLETED)
            ->home_score->not->toBeNull()
            ->away_score->not->toBeNull();
    }
});
