<?php

use App\Models\User;
use App\Models\Role;
use App\Models\League;
use App\Models\Season;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::ADMIN]);
    $this->actingAs($this->user);
});

test('an admin can view all users', function () {
    $users = User::factory(10)->create();

    $response = get(route('admin.users'));

    $response->assertStatus(200);
});

test('an admin can view all leagues', function () {
    $response = get(route('admin.leagues.index'));

    $response->assertStatus(200);
});

test('an admin can view all seasons', function () {
    $response = get(route('admin.seasons.index'));

    $response->assertStatus(200);
});

test('an admin can create a league', function () {
    $league = League::factory()->make();
    $response = post(route('admin.leagues.store'), $league->toArray());

    $response->assertRedirect(route('admin.leagues.index'));

    $this->assertDatabaseHas('leagues', $league->toArray());
});

test('an admin can create a season', function () {
    $season = Season::factory()->make(['name' => 'TEST SEASON WOW!']);
    $response = post(route('admin.seasons.store'), $season->toArray());

    $response->assertRedirect(route('admin.seasons.index'));

    $this->assertDatabaseHas('seasons', ['name' => 'TEST SEASON WOW!']);
});
