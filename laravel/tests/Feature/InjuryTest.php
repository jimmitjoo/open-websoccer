<?php

use App\Models\Player;
use App\Models\Game;
use App\Models\TrainingType;
use App\Models\InjuryType;
use App\Models\Injury;
use App\Services\InjuryService;
use App\Services\TrainingService;
use Carbon\Carbon;

beforeEach(function () {
    $this->seed(\Database\Seeders\InjuryTypeSeeder::class);
    $this->injuryService = app(InjuryService::class);
});

it('can create an injury for a player during a match', function () {
    $player = Player::factory()->create();
    $game = Game::factory()->create();
    $injuryType = InjuryType::factory()->create([
        'name' => 'muscle_strain_minor',
        'min_days' => 3,
        'max_days' => 7,
        'severity' => 'minor'
    ]);

    $injury = $this->injuryService->createMatchInjury($player, $game);

    expect($injury)
        ->toBeInstanceOf(Injury::class)
        ->player_id->toBe($player->id)
        ->match_id->toBe($game->id)
        ->actual_return_at->toBeNull();

    expect($injury->expected_return_at)
        ->toBeInstanceOf(Carbon::class);
});

it('can heal an injury', function () {
    $injury = Injury::factory()->create([
        'actual_return_at' => null
    ]);

    $this->injuryService->healInjury($injury);

    expect($injury->fresh())
        ->actual_return_at->not->toBeNull()
        ->actual_return_at->toBeInstanceOf(Carbon::class);
});

it('returns the correct injury type based on randomness', function () {
    // Create some injury types of each severity
    InjuryType::factory()->create(['severity' => 'minor']);
    InjuryType::factory()->create(['severity' => 'moderate']);
    InjuryType::factory()->create(['severity' => 'severe']);

    // Run many times to test the probability distribution
    $results = collect(range(1, 1000))->map(function () {
        return $this->injuryService->getRandomInjuryType()->severity;
    });

    // Check that the distribution is approximately correct
    $minorCount = $results->filter(fn ($s) => $s === 'minor')->count();
    $moderateCount = $results->filter(fn ($s) => $s === 'moderate')->count();
    $severeCount = $results->filter(fn ($s) => $s === 'severe')->count();

    expect($minorCount)->toBeGreaterThan(500); // ~60%
    expect($moderateCount)->toBeGreaterThan(250); // ~30%
    expect($severeCount)->toBeLessThan(150); // ~10%
});

it('marks a player as injured when they have an active injury', function () {
    $player = Player::factory()->create();

    expect($player->isInjured())->toBeFalse();

    Injury::factory()->create([
        'player_id' => $player->id,
        'actual_return_at' => null,
        'expected_return_at' => now()->addDays(7)
    ]);

    expect($player->fresh()->isInjured())->toBeTrue();
});

it('reduces training effects for injured players', function () {
    $player = Player::factory()->create();
    $game = Game::factory()->create();

    // Injure the player
    $this->injuryService->createMatchInjury($player, $game);

    // Try to train the player
    $trainingType = TrainingType::factory()->create([
        'effects' => ['stamina' => 10]
    ]);

    $trainingService = app(TrainingService::class);
    $trainingSession = $trainingService->scheduleTraining([
        'club_id' => $player->club_id,
        'training_type_id' => $trainingType->id,
        'date' => now()->addDays(1),
        'player_ids' => [$player->id]
    ]);
    $trainingService->executeTraining($trainingSession);

    // Check that the training effect was reduced by 75%
    expect($player->fresh()->stamina)
        ->toBeLessThanOrEqual(round($player->stamina + (10 * 0.25)));
});

it('can have multiple injuries on different players', function () {
    $game = Game::factory()->create();
    $players = Player::factory(3)->create();

    foreach ($players as $player) {
        $this->injuryService->createMatchInjury($player, $game);
    }

    $activeInjuries = Injury::whereNull('actual_return_at')->count();
    expect($activeInjuries)->toBe(3);
});
