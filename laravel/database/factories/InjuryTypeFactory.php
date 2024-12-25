<?php

namespace Database\Factories;

use App\Models\InjuryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class InjuryTypeFactory extends Factory
{
    protected $model = InjuryType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'min_days' => $this->faker->numberBetween(3, 30),
            'max_days' => $this->faker->numberBetween(31, 90),
            'severity' => $this->faker->randomElement(['minor', 'moderate', 'severe'])
        ];
    }

    public function minor(): self
    {
        return $this->state(fn (array $attributes) => [
            'min_days' => 3,
            'max_days' => 14,
            'severity' => 'minor'
        ]);
    }

    public function moderate(): self
    {
        return $this->state(fn (array $attributes) => [
            'min_days' => 14,
            'max_days' => 45,
            'severity' => 'moderate'
        ]);
    }

    public function severe(): self
    {
        return $this->state(fn (array $attributes) => [
            'min_days' => 45,
            'max_days' => 270,
            'severity' => 'severe'
        ]);
    }
}
