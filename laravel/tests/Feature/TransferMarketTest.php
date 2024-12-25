<?php

use App\Models\User;
use App\Models\Player;
use App\Models\Club;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Enums\TransferListingStatus;
use App\Enums\TransferOfferStatus;
use App\Models\Contract;
use App\Models\Role;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::MANAGER]);
    $this->club = Club::factory()->create(['user_id' => $this->user->id, 'balance' => 100000000]); // Ändrat från team till club
    $this->player = Player::factory()->create(['club_id' => $this->club->id]);
    $this->contract = Contract::factory()->create([
        'player_id' => $this->player->id,
        'club_id' => $this->club->id,
        'salary' => 10000,
        'start_date' => now(),
        'end_date' => now()->addYears(2),
        'termination_fee' => 50000
    ]);

    $this->actingAs($this->user);
});

test('a manager can view their own transfer listings', function () {
    $listings = TransferListing::factory(3)->create([
        'club_id' => $this->club->id,
        'player_id' => $this->player->id
    ]);

    $response = get(route('transfer-market.my-listings'));

    $response->assertStatus(200)
        ->assertViewIs('transfer-market.my-listings')
        ->assertViewHas('listings');
});

test('a manager can list a player for transfer', function () {

    $listingData = [
        'player_id' => $this->player->id,
        'asking_price' => 1000000
    ];

    $response = post(route('transfer-market.list-player', $this->player->id), $listingData);

    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('transfer_listings', [
        'player_id' => $this->player->id,
        'club_id' => $this->club->id,
        'asking_price' => 1000000,
        'status' => TransferListingStatus::ACTIVE
    ]);
});

test('a manager can make an offer on a transfer listing', function () {
    $sellerUser = User::factory()->create();
    $sellerClub = Club::factory()->create(['user_id' => $sellerUser->id]);
    $listedPlayer = Player::factory()->create(['club_id' => $sellerClub->id]);

    $askingPrice = 1000000;
    $listing = TransferListing::factory()->create([
        'club_id' => $sellerClub->id,
        'player_id' => $listedPlayer->id,
        'asking_price' => $askingPrice
    ]);

    $offerData = [
        'amount' => $askingPrice + 100000,
        'transfer_listing_id' => $listing->id
    ];

    $response = post(route('transfer-offers.store', $listing->id), $offerData);

    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('transfer_offers', [
        'transfer_listing_id' => $listing->id,
        'bidding_club_id' => $this->club->id,
        'amount' => $askingPrice + 100000,
        'status' => TransferOfferStatus::PENDING
    ]);
});

test('a manager cannot make an offer on their own transfer listing', function () {
    $listing = TransferListing::factory()->create([
        'club_id' => $this->club->id,
        'player_id' => $this->player->id
    ]);

    $offerData = [
        'amount' => 900000,
        'transfer_listing_id' => $listing->id
    ];

    $response = post(route('transfer-offers.store', $listing->id), $offerData);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('transfer_offers', [
        'transfer_listing_id' => $listing->id,
        'bidding_club_id' => $this->club->id
    ]);
});

test('a manager can accept an offer on a transfer listing', function () {
    $listing = TransferListing::factory()->create([
        'club_id' => $this->club->id,
        'player_id' => $this->player->id
    ]);
    $offer = TransferOffer::factory()->create([
        'transfer_listing_id' => $listing->id,
        'bidding_club_id' => $this->club->id
    ]);

    $response = post(route('transfer-offers.accept', $offer->id));

    $response->assertJson([
        'success' => true,
    ]);
});


test('a manager can reject an offer on a transfer listing', function () {
    $listing = TransferListing::factory()->create([
        'club_id' => $this->club->id,
        'player_id' => $this->player->id
    ]);
    $offer = TransferOffer::factory()->create([
        'transfer_listing_id' => $listing->id,
        'bidding_club_id' => $this->club->id
    ]);

    $response = post(route('transfer-offers.reject', $offer->id));

    $response->assertJson([
        'success' => true,
    ]);
});

test('a manager can withdraw an offer on a transfer listing', function () {
    $offer = TransferOffer::factory()->create([
        'bidding_club_id' => $this->club->id
    ]);

    $response = post(route('transfer-market.offers.withdraw', $offer->id));

    $response->assertJson([
        'success' => true,
    ]);
});
