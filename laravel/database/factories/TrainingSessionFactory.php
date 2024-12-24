<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\TrainingType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSession>
 */
class TrainingSessionFactory extends Factory
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
            'training_type_id' => TrainingType::factory(),
            'scheduled_date' => now()->addDays(rand(1, 10)),
            'status' => 'scheduled'
        ];
    }
}
