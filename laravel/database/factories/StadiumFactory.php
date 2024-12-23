<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stadium>
 */
class StadiumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'capacity_seats' => fake()->numberBetween(10000, 100000),
            'capacity_stands' => fake()->numberBetween(10000, 100000),
            'capacity_vip' => fake()->numberBetween(10000, 100000),
            'level_pitch' => fake()->numberBetween(1, 10),
            'level_seats' => fake()->numberBetween(1, 10),
            'level_stands' => fake()->numberBetween(1, 10),
            'level_vip' => fake()->numberBetween(1, 10),
            'maintenance_pitch' => fake()->numberBetween(1, 10),
            'maintenance_facilities' => fake()->numberBetween(1, 10),
            'price_seats' => fake()->numberBetween(10, 100),
            'price_stands' => fake()->numberBetween(10, 100),
            'price_vip' => fake()->numberBetween(10, 100),
        ];
    }
}
