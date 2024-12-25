<?php

namespace Database\Factories;

use App\Models\Injury;
use App\Models\Player;
use App\Models\Game;
use App\Models\InjuryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class InjuryFactory extends Factory
{
    protected $model = Injury::class;

    public function definition(): array
    {
        $injuryType = InjuryType::factory()->create();
        $recoveryDays = $this->faker->numberBetween($injuryType->min_days, $injuryType->max_days);

        return [
            'player_id' => Player::factory(),
            'injury_type_id' => $injuryType->id,
            'match_id' => Game::factory(),
            'started_at' => now(),
            'expected_return_at' => now()->addDays($recoveryDays),
            'actual_return_at' => null
        ];
    }

    public function healed(): self
    {
        return $this->state(fn (array $attributes) => [
            'actual_return_at' => now()
        ]);
    }
}
