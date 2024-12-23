<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\TransferListing;
use App\Enums\TransferOfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferOffer>
 */
class TransferOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transfer_listing_id' => TransferListing::factory(),
            'bidding_club_id' => Club::factory(),
            'amount' => fake()->numberBetween(100000, 1000000),
            'status' => TransferOfferStatus::PENDING,
            'deadline' => now()->addDays(7),
            'message' => fake()->sentence(),
        ];
    }
}
