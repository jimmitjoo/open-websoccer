<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\YouthAcademyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class YouthAcademyLevelFactory extends Factory
{
    protected $model = YouthAcademyLevel::class;

    public function definition(): array
    {
        $level = $this->faker->numberBetween(1, 10);
        $baseMonthly = 50000;
        $baseUpgrade = 500000;

        return [
            'name' => "Nivå {$level}",
            'level' => $level,
            'monthly_cost' => $baseMonthly * pow(1.2, $level - 1),
            'upgrade_cost' => $baseUpgrade * pow(1.5, $level - 1),
            'max_youth_players' => min(5 + floor($level / 2), 20),
            'youth_player_generation_rate' => max(30 - $level, 7),
            'min_potential_rating' => min(40 + $level * 2, 70),
            'max_potential_rating' => min(60 + $level * 2, 99),
            'training_efficiency_bonus' => $level * 5,
        ];
    }

    public function withLevel(int $level): self
    {
        return $this->state(fn (array $attributes) => [
            'name' => "Nivå {$level}",
            'level' => $level,
            'monthly_cost' => 50000 * pow(1.2, $level - 1),
            'upgrade_cost' => 500000 * pow(1.5, $level - 1),
            'max_youth_players' => min(5 + floor($level / 2), 20),
            'youth_player_generation_rate' => max(30 - $level, 7),
            'min_potential_rating' => min(40 + $level * 2, 70),
            'max_potential_rating' => min(60 + $level * 2, 99),
            'training_efficiency_bonus' => $level * 5,
        ]);
    }
}
