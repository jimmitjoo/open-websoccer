<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Club;
use App\Enums\TransferListingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferListing>
 */
class TransferListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'club_id' => Club::factory(),
            'asking_price' => fake()->numberBetween(1000, 100000),
            'status' => TransferListingStatus::ACTIVE,
            'deadline' => fake()->dateTime(),
        ];
    }
}
