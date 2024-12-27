<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Club;
use App\Models\YouthAcademy;
use App\Models\YouthPlayer;
use App\Models\YouthAcademyLevel;
use App\Services\YouthAcademyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Player;

class YouthAcademyServiceTest extends TestCase
{
    use RefreshDatabase;

    private YouthAcademyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new YouthAcademyService();
    }

    public function test_it_creates_academy_for_club(): void
    {
        $club = Club::factory()->create();
        $level = YouthAcademyLevel::factory()->create();

        $academy = $this->service->createAcademyForClub($club, $level);

        $this->assertInstanceOf(YouthAcademy::class, $academy);
        $this->assertEquals($club->id, $academy->club_id);
        $this->assertEquals($level->id, $academy->youth_academy_level_id);
        $this->assertNotNull($academy->next_youth_player_available_at);
    }

    public function test_it_generates_youth_player_when_conditions_met(): void
    {
        $level = YouthAcademyLevel::factory()->create([
            'max_youth_players' => 5,
            'youth_player_generation_rate' => 7,
        ]);

        $academy = YouthAcademy::factory()
            ->withLevel($level)
            ->create([
                'next_youth_player_available_at' => now()->subDay(),
            ]);

        $player = $this->service->generateYouthPlayer($academy);

        $this->assertInstanceOf(YouthPlayer::class, $player);
        $this->assertEquals($academy->id, $player->youth_academy_id);
        $this->assertBetween(
            $player->potential_rating,
            $level->min_potential_rating,
            $level->max_potential_rating
        );
    }

    public function test_it_does_not_generate_player_when_academy_is_full(): void
    {
        $level = YouthAcademyLevel::factory()->create([
            'max_youth_players' => 1,
        ]);

        $academy = YouthAcademy::factory()
            ->withLevel($level)
            ->create();

        YouthPlayer::factory()->forAcademy($academy)->create();

        $player = $this->service->generateYouthPlayer($academy);

        $this->assertNull($player);
    }

    public function test_it_develops_player_through_training(): void
    {
        $player = YouthPlayer::factory()->create([
            'strength' => 50,
            'determination' => 80,
            'work_rate' => 70,
        ]);

        $log = $this->service->developPlayer($player, 'strength', 'training');

        $this->assertEquals('strength', $log->attribute_name);
        $this->assertEquals(50, $log->old_value);
        $this->assertNotEquals(50, $log->new_value);
        $this->assertEquals('training', $log->development_type);
        $this->assertNotNull($log->note);

        $player->refresh();
        $this->assertNotEquals(50, $player->strength);
    }

    public function test_it_promotes_youth_player_to_senior_team(): void
    {
        $player = YouthPlayer::factory()->create([
            'promotion_available_at' => now()->subDay(),
            'potential_rating' => 75,
            'current_ability' => 60,
        ]);

        $seniorPlayer = $this->service->promoteToSeniorTeam($player);

        $this->assertInstanceOf(Player::class, $seniorPlayer);
        $this->assertEquals($player->first_name, $seniorPlayer->first_name);
        $this->assertEquals($player->strength, $seniorPlayer->strength);
        $this->assertTrue($seniorPlayer->hasActiveContract());
        $this->assertSoftDeleted($player);
    }

    public function test_it_throws_exception_when_promoting_unavailable_player(): void
    {
        $player = YouthPlayer::factory()->create([
            'promotion_available_at' => now()->addDays(30),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->promoteToSeniorTeam($player);
    }

    private function assertBetween(int $actual, int $min, int $max): void
    {
        $this->assertGreaterThanOrEqual($min, $actual);
        $this->assertLessThanOrEqual($max, $actual);
    }
}
