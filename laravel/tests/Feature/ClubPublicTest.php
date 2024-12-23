<?php

use App\Models\Club;
use App\Models\Season;
use App\Models\Game;
use App\Models\League;
use App\Models\Stadium;

use function Pest\Laravel\{get, post};
beforeEach(function () {
    $this->club = Club::factory()->create();
    $this->season = Season::factory()->create();
});

test('a user can view a club', function () {
    $league = League::factory()->create();
    $game = Game::factory()->create(['home_club_id' => $this->club->id, 'season_id' => $this->season->id, 'league_id' => $league->id]);

    $response = get(route('clubs.show', ['club' => $this->club->id]));

    $response->assertStatus(200);
});

test('a user can view a club matches', function () {

    $response = get(route('clubs.matches', ['club' => $this->club->id]));

    $response->assertStatus(200);
});

test('a user can view a club squad', function () {

    $response = get(route('clubs.squad', ['club' => $this->club->id]));

    $response->assertStatus(200);
});
