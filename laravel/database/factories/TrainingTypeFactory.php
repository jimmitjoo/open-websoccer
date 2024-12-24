<?php

namespace Database\Factories;

use App\Models\TrainingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingTypeFactory extends Factory
{
    protected $model = TrainingType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->regexify('[A-Z]{2}'),
            'effects' => [
                'technique' => $this->faker->numberBetween(-2, 2),
                'stamina' => $this->faker->numberBetween(-2, 2),
                'strength' => $this->faker->numberBetween(-2, 2)
            ],
            'intensity' => $this->faker->numberBetween(1, 5)
        ];
    }
}
