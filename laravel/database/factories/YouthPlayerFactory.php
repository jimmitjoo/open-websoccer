<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\YouthPlayer;
use App\Models\YouthAcademy;
use Illuminate\Database\Eloquent\Factories\Factory;

class YouthPlayerFactory extends Factory
{
    protected $model = YouthPlayer::class;

    public function definition(): array
    {
        $positions = ['GK', 'RB', 'CB', 'LB', 'DM', 'CM', 'AM', 'RW', 'ST', 'LW'];
        $countries = ['SE', 'EN', 'ES', 'IT', 'DE'];

        return [
            'youth_academy_id' => YouthAcademy::factory(),
            'first_name' => $this->faker->firstName('male'),
            'last_name' => $this->faker->lastName(),
            'age' => $this->faker->numberBetween(15, 19),
            'nationality' => $this->faker->randomElement($countries),
            'preferred_position' => $this->faker->randomElement($positions),
            'potential_rating' => $this->faker->numberBetween(40, 99),
            'current_ability' => $this->faker->numberBetween(20, 60),
            'development_progress' => $this->faker->numberBetween(0, 100),
            'promotion_available_at' => $this->faker->optional(0.2)->dateTimeBetween('now', '+1 year'),

            // Grundläggande attribut
            'strength' => $this->faker->numberBetween(20, 60),
            'speed' => $this->faker->numberBetween(20, 60),
            'technique' => $this->faker->numberBetween(20, 60),
            'passing' => $this->faker->numberBetween(20, 60),
            'shooting' => $this->faker->numberBetween(20, 60),
            'heading' => $this->faker->numberBetween(20, 60),
            'tackling' => $this->faker->numberBetween(20, 60),
            'ball_control' => $this->faker->numberBetween(20, 60),
            'stamina' => $this->faker->numberBetween(20, 60),
            'keeper_ability' => $this->faker->numberBetween(20, 60),

            // Personlighetsattribut
            'determination' => $this->faker->numberBetween(20, 99),
            'work_rate' => $this->faker->numberBetween(20, 99),
            'leadership' => $this->faker->numberBetween(20, 99),
        ];
    }

    public function forAcademy(YouthAcademy $academy): self
    {
        return $this->state(function (array $attributes) use ($academy) {
            $minPotential = $academy->level->min_potential_rating;
            $maxPotential = $academy->level->max_potential_rating;

            return [
                'youth_academy_id' => $academy->id,
                'potential_rating' => $this->faker->numberBetween($minPotential, $maxPotential),
            ];
        });
    }

    public function goalkeeper(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'preferred_position' => 'GK',
                'keeper_ability' => $this->faker->numberBetween(40, 70),
            ];
        });
    }

    public function highPotential(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'potential_rating' => $this->faker->numberBetween(80, 99),
                'determination' => $this->faker->numberBetween(70, 99),
                'work_rate' => $this->faker->numberBetween(70, 99),
            ];
        });
    }
}
