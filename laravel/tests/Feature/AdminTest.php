<?php

use App\Models\User;
use App\Models\Role;
use App\Models\League;
use App\Models\Season;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    $this->regularManager = User::factory()->create(['role' => Role::MANAGER]);
    $this->admin = User::factory()->create(['role' => Role::ADMIN]);
});

test('a regular manager cannot view all users', function () {
    $this->actingAs($this->regularManager);
    $users = User::factory(10)->create();

    $response = get(route('admin.users'));

    $response->assertStatus(403);
});

test('an admin can view all leagues', function () {
    $this->actingAs($this->admin);
    $leagues = League::factory(10)->create();

    $response = get(route('admin.leagues.index'));

    $response->assertStatus(200);
});

test('a regular manager cannot view all leagues', function () {
    $this->actingAs($this->regularManager);
    $leagues = League::factory(10)->create();

    $response = get(route('admin.leagues.index'));

    $response->assertStatus(403);
});

test('an admin can view all seasons', function () {
    $this->actingAs($this->admin);

    $response = get(route('admin.seasons.index'));

    $response->assertStatus(200);
});

test('a regular manager cannot create a league', function () {
    $this->actingAs($this->regularManager);
    $league = League::factory()->make();

    $response = post(route('admin.leagues.store'), $league->toArray());

    $response->assertStatus(403);
});

test('an admin can create a league', function () {
    $this->actingAs($this->admin);
    $league = League::factory()->make();
    $response = post(route('admin.leagues.store'), $league->toArray());

    $response->assertRedirect(route('admin.leagues.index'));

    $this->assertDatabaseHas('leagues', $league->toArray());
});

test('a regular manager cannot create a season', function () {
    $this->actingAs($this->regularManager);
    $season = Season::factory()->make(['name' => 'REGULAR TEST SEASON WOW!']);
    $response = post(route('admin.seasons.store'), $season->toArray());

    $response->assertStatus(403);
});

test('an admin can create a season', function () {
    $this->actingAs($this->admin);
    $season = Season::factory()->make(['name' => 'TEST SEASON WOW!']);
    $response = post(route('admin.seasons.store'), $season->toArray());

    $response->assertRedirect(route('admin.seasons.index'));

    $this->assertDatabaseHas('seasons', ['name' => 'TEST SEASON WOW!']);
});
