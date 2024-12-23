<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\League>
 */
class LeagueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'rank' => 1,
            'max_teams' => 10,
            'has_relegation' => false,
            'has_promotion' => false,
            'is_active' => true,
            'country_code' => 'SE',
            'level' => 'national',
        ];
    }
}
