<?php

use App\Models\User;
use App\Models\Player;
use App\Models\Club;  // Ändrat från Team till Club
use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Enums\TransferListingStatus;
use App\Enums\TransferOfferStatus;
use App\Models\Contract;
use App\Models\Role;

use function Pest\Laravel\{get, post};

beforeEach(function () {
    // Skapa en testanvändare med ett lag och spelare
    $this->user = User::factory()->create(['role' => Role::MANAGER]);
    $this->club = Club::factory()->create(['user_id' => $this->user->id, 'balance' => 100000000]); // Ändrat från team till club
    $this->player = Player::factory()->create(['club_id' => $this->club->id]); // Ändrat från team_id till club_id
    // Skapa ett kontrakt mellan klubben och spelaren
    $this->contract = Contract::factory()->create([
        'player_id' => $this->player->id,
        'club_id' => $this->club->id,
        'salary' => 10000,
        'start_date' => now(),
        'end_date' => now()->addYears(2),
        'termination_fee' => 50000
    ]);

    // Logga in användaren
    $this->actingAs($this->user);
});

test('användare kan se sina transfer listings', function () {
    // Skapa några transfer listings för användaren
    $listings = TransferListing::factory(3)->create([
        'club_id' => $this->club->id,  // Ändrat från team_id till club_id
        'player_id' => $this->player->id
    ]);

    $response = get(route('transfer-market.my-listings'));

    $response->assertStatus(200)
        ->assertViewIs('transfer-market.my-listings')
        ->assertViewHas('listings');
});

test('användare kan skapa en ny transfer listing', function () {

    $listingData = [
        'player_id' => $this->player->id,
        'asking_price' => 1000000
    ];

    /*
    Route::post('/transfer-market/players/{player}/list', [TransferMarketController::class, 'listPlayer'])
        ->name('transfer-market.list-player');
        */

    $response = post(route('transfer-market.list-player', $this->player->id), $listingData);

    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('transfer_listings', [
        'player_id' => $this->player->id,
        'club_id' => $this->club->id,  // Ändrat från team_id till club_id
        'asking_price' => 1000000,
        'status' => TransferListingStatus::ACTIVE
    ]);
});

test('användare kan lägga ett bud på en transfer listing', function () {
    // Skapa en annan användare med lag som äger transfer listing
    $sellerUser = User::factory()->create();
    $sellerClub = Club::factory()->create(['user_id' => $sellerUser->id]); // Ändrat från team till club
    $listedPlayer = Player::factory()->create(['club_id' => $sellerClub->id]); // Ändrat från team_id till club_id

    $askingPrice = 1000000;
    $listing = TransferListing::factory()->create([
        'club_id' => $sellerClub->id,  // Ändrat från team_id till club_id
        'player_id' => $listedPlayer->id,
        'asking_price' => $askingPrice
    ]);

    $offerData = [
        'amount' => $askingPrice + 100000,
        'transfer_listing_id' => $listing->id
    ];

    /* Route::post('/transfer-market/listings/{listing}/offers', [TransferOfferController::class, 'store'])
        ->name('transfer-offers.store');
    */

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

test('användare kan inte lägga bud på sin egen transfer listing', function () {
    $listing = TransferListing::factory()->create([
        'club_id' => $this->club->id,  // Ändrat från team_id till club_id
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
        'bidding_club_id' => $this->club->id  // Ändrat från bidding_team_id till bidding_club_id
    ]);
});
