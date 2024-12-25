<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'strength' => fake()->numberBetween(1, 100),
            'form' => fake()->numberBetween(1, 100),
            'stamina' => fake()->numberBetween(1, 100),
            'speed' => fake()->numberBetween(1, 100),
            'technique' => fake()->numberBetween(1, 100),
            'passing' => fake()->numberBetween(1, 100),
            'goalkeeper' => fake()->numberBetween(1, 100),
            'defense' => fake()->numberBetween(1, 100),
            'midfield' => fake()->numberBetween(1, 100),
            'striker' => fake()->numberBetween(1, 100),
            'birth_date' => fake()->date(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'position' => fake()->randomElement(['GK', 'DEF', 'MID', 'FWD']),
        ];
    }
}
