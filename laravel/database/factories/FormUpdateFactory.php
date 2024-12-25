<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormUpdate>
 */
class FormUpdateFactory extends Factory
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
            'old_value' => fake()->numberBetween(0, 100),
            'new_value' => fake()->numberBetween(0, 100),
            'reason' => fake()->sentence(),
            'adjusted_by' => User::factory(),
        ];
    }
}
