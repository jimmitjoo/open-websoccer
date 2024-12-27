<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use Tests\TestCase;
use App\Models\YouthPlayer;
use App\Models\YouthAcademy;
use App\Models\YouthAcademyLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YouthPlayerFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_youth_player_with_valid_data(): void
    {
        $player = YouthPlayer::factory()->create();

        $this->assertNotNull($player->youth_academy_id);
        $this->assertNotNull($player->first_name);
        $this->assertNotNull($player->last_name);
        $this->assertContains($player->nationality, ['SE', 'EN', 'ES', 'IT', 'DE']);
        $this->assertContains($player->preferred_position, ['GK', 'RB', 'CB', 'LB', 'DM', 'CM', 'AM', 'RW', 'ST', 'LW']);
        $this->assertBetween($player->age, 15, 19);
        $this->assertBetween($player->potential_rating, 40, 99);
        $this->assertBetween($player->current_ability, 20, 60);
    }

    public function test_it_creates_youth_player_for_specific_academy(): void
    {
        $level = YouthAcademyLevel::factory()->create([
            'min_potential_rating' => 50,
            'max_potential_rating' => 80,
        ]);

        $academy = YouthAcademy::factory()
            ->withLevel($level)
            ->create();

        $player = YouthPlayer::factory()
            ->forAcademy($academy)
            ->create();

        $this->assertEquals($academy->id, $player->youth_academy_id);
        $this->assertBetween($player->potential_rating, 50, 80);
    }

    public function test_it_creates_goalkeeper_with_higher_keeper_ability(): void
    {
        $player = YouthPlayer::factory()
            ->goalkeeper()
            ->create();

        $this->assertEquals('GK', $player->preferred_position);
        $this->assertBetween($player->keeper_ability, 40, 70);
    }

    public function test_it_creates_high_potential_player(): void
    {
        $player = YouthPlayer::factory()
            ->highPotential()
            ->create();

        $this->assertBetween($player->potential_rating, 80, 99);
        $this->assertBetween($player->determination, 70, 99);
        $this->assertBetween($player->work_rate, 70, 99);
    }

    private function assertBetween(int $actual, int $min, int $max): void
    {
        $this->assertGreaterThanOrEqual($min, $actual);
        $this->assertLessThanOrEqual($max, $actual);
    }
}
