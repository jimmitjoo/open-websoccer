<?php

use App\Models\User;
use App\Models\Club;
use App\Models\Role;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    // Skapa en testanvändare med ett lag och spelare
    $this->user = User::factory()->create(['role' => Role::MANAGER]);

    $this->actingAs($this->user);
});

test('a manager can take over a club', function () {
    $club = Club::factory()->create(['user_id' => null]);

    $response = post(route('become-manager', ['club_id' => $club->id]));

    $this->assertDatabaseHas(Club::class, [
        'id' => $club->id,
        'user_id' => auth()->id()
    ]);
});

test('a manager cannot take over a club that already has a manager', function () {
    $club = Club::factory()->create(['user_id' => User::factory()->create()->id]);

    $response = post(route('become-manager', ['club_id' => $club->id]));

    $this->assertDatabaseMissing(Club::class, [
        'id' => $club->id,
        'user_id' => auth()->id()
    ]);
});

test('a manager can visit the clubhouse', function () {
    $club = Club::factory()->create(['user_id' => auth()->id()]);

    $response = get(route('clubhouse'));

    $response->assertStatus(200);
});

test('a manager cannot visit the clubhouse if they do not have a club', function () {
    $response = get(route('clubhouse'));

    $response->assertRedirect(route('choose-club'));
});

test('a manager can visit the finance page', function () {
    $club = Club::factory()->create(['user_id' => auth()->id()]);

    $response = get(route('club.finance'));

    $response->assertStatus(200);
});

test('a manager cannot visit the finance page if they do not have a club', function () {
    $response = get(route('club.finance'));

    $response->assertRedirect(route('choose-club'));
});

