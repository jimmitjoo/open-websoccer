<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\YouthPlayer;
use App\Models\YouthPlayerDevelopmentLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class YouthPlayerDevelopmentLogFactory extends Factory
{
    protected $model = YouthPlayerDevelopmentLog::class;

    public function definition(): array
    {
        $attributes = [
            'strength', 'speed', 'technique', 'passing',
            'shooting', 'heading', 'tackling', 'ball_control',
            'stamina', 'keeper_ability', 'determination',
            'work_rate', 'leadership'
        ];

        $developmentTypes = ['training', 'natural', 'event', 'mentor'];
        $attribute = $this->faker->randomElement($attributes);
        $oldValue = $this->faker->numberBetween(20, 95);
        $change = $this->faker->numberBetween(-5, 10);

        return [
            'youth_player_id' => YouthPlayer::factory(),
            'attribute_name' => $attribute,
            'old_value' => $oldValue,
            'new_value' => min(99, max(1, $oldValue + $change)),
            'development_type' => $this->faker->randomElement($developmentTypes),
            'note' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    public function training(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'development_type' => 'training',
                'note' => 'Utveckling genom träning',
            ];
        });
    }

    public function natural(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'development_type' => 'natural',
                'note' => 'Naturlig utveckling',
            ];
        });
    }

    public function positiveChange(): self
    {
        return $this->state(function (array $attributes) {
            $oldValue = $this->faker->numberBetween(20, 90);
            return [
                'old_value' => $oldValue,
                'new_value' => $oldValue + $this->faker->numberBetween(1, 5),
            ];
        });
    }

    public function negativeChange(): self
    {
        return $this->state(function (array $attributes) {
            $oldValue = $this->faker->numberBetween(20, 99);
            return [
                'old_value' => $oldValue,
                'new_value' => $oldValue - $this->faker->numberBetween(1, 5),
            ];
        });
    }
}
