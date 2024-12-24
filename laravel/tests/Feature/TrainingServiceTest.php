<?php

use App\Models\Club;
use App\Models\Player;
use App\Models\TrainingType;
use App\Models\TrainingSession;
use App\Services\TrainingService;

beforeEach(function () {
    $this->service = app(TrainingService::class);

    $this->club = Club::factory()->create();

    $this->player = Player::factory()->create([
        'club_id' => $this->club->id,
        'strength' => 50,
        'stamina' => 50,
        'technique' => 50,
        'form' => 0,
        'injured' => false
    ]);

    $this->trainingType = TrainingType::factory()->create([
        'name' => 'Teknikträning',
        'effects' => [
            'technique' => 2,
            'stamina' => -1,
            'strength' => -1
        ]
    ]);

    $this->session = TrainingSession::factory()->create([
        'club_id' => $this->club->id,
        'training_type_id' => $this->trainingType->id,
        'status' => 'scheduled'
    ]);

    $this->session->players()->attach($this->player->id);
});

test('it applies training effects correctly', function () {
    $this->service->executeTraining($this->session);

    $this->player->refresh();

    expect($this->player->technique)->not->toBe(50)
        ->and($this->player->stamina)->not->toBe(50)
        ->and($this->player->strength)->not->toBe(50);
});

test('it reduces training effects for injured players', function () {
    // Spara ursprunglig technique-värde
    $originalTechnique = $this->player->technique;

    // Uppdatera spelaren till skadad och verifiera uppdateringen
    $this->player->injured = true;
    $this->player->save();

    // Verifiera att spelaren verkligen är skadad
    expect($this->player->fresh()->injured)->toBeTrue();

    $this->service->executeTraining($this->session);

    // Hämta spelarens nya värden från databasen
    $updatedPlayer = $this->player->fresh();

    // Verifiera att technique-ökningen är mindre än för oskadade spelare
    expect($updatedPlayer->technique)
        ->toBeLessThan($originalTechnique + 2)
        ->and($updatedPlayer->injured)->toBeTrue();
});

test('it marks session as completed', function () {
    $this->service->executeTraining($this->session);

    $this->session->refresh();

    expect($this->session->status)->toBe('completed');
});

test('it keeps attributes within valid range', function () {
    $this->player->update([
        'technique' => 99,
        'stamina' => 2,
        'strength' => 50
    ]);

    $this->service->executeTraining($this->session);

    $this->player->refresh();

    expect($this->player->technique)->toBeLessThanOrEqual(100)
        ->and($this->player->stamina)->toBeGreaterThanOrEqual(1);
});

test('it updates player form', function () {
    $this->player->update(['form' => 0]);

    $this->service->executeTraining($this->session);

    $this->player->refresh();

    expect($this->player->form)->toBeGreaterThan(0);
});
