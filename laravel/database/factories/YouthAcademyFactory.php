<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use App\Models\YouthAcademy;
use App\Models\YouthAcademyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class YouthAcademyFactory extends Factory
{
    protected $model = YouthAcademy::class;

    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'youth_academy_level_id' => YouthAcademyLevel::factory(),
            'next_youth_player_available_at' => $this->faker->dateTimeBetween('now', '+30 days'),
            'total_investment' => $this->faker->numberBetween(100000, 1000000),
        ];
    }

    public function forClub(Club $club): self
    {
        return $this->state(function (array $attributes) use ($club) {
            return [
                'club_id' => $club->id,
            ];
        });
    }

    public function withLevel(YouthAcademyLevel $level): self
    {
        return $this->state(function (array $attributes) use ($level) {
            return [
                'youth_academy_level_id' => $level->id,
            ];
        });
    }
}
