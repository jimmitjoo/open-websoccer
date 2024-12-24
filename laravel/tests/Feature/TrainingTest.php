<?php

use App\Models\User;
use App\Models\Club;
use App\Models\Player;
use App\Models\TrainingType;
use App\Models\TrainingSession;
use App\Models\Role;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::MANAGER]);
    $this->club = Club::factory()->create([
        'user_id' => $this->user->id
    ]);
    $this->player = Player::factory()->create([
        'club_id' => $this->club->id,
        'strength' => 50,
        'stamina' => 50,
        'technique' => 50,
        'form' => 0
    ]);

    $this->trainingType = TrainingType::factory()->create([
        'name' => 'Teknikträning',
        'effects' => [
            'technique' => 2,
            'stamina' => -1,
            'strength' => -1
        ]
    ]);

    $this->actingAs($this->user);
});

test('manager can view training page', function () {
    get(route('training.index'))
        ->assertStatus(200)
        ->assertSee('Träning')
        ->assertSee('Schemalägg träning');
});

test('manager can schedule training session', function () {
    $response = post(route('training.schedule'), [
        'training_type_id' => $this->trainingType->id,
        'player_ids' => [$this->player->id],
        'date' => now()->addDay()->toDateString()
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('training_sessions', [
        'club_id' => $this->club->id,
        'training_type_id' => $this->trainingType->id,
        'status' => 'scheduled'
    ]);
});

test('cannot schedule training for players from other clubs', function () {
    $otherPlayer = Player::factory()->create();

    $response = post(route('training.schedule'), [
        'training_type_id' => $this->trainingType->id,
        'player_ids' => [$otherPlayer->id],
        'date' => now()->addDay()->toDateString()
    ]);

    $response->assertSessionHasErrors(['player_ids']);
});
