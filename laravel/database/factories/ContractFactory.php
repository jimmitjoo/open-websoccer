<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
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
            'salary' => fake()->numberBetween(1000, 100000),
            'start_date' => now()->subYears(1),
            'end_date' => now()->addYears(2),
            'termination_fee' => fake()->numberBetween(1000, 100000)
        ];
    }
}
