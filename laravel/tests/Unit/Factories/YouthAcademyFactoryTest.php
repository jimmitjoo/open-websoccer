<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use Tests\TestCase;
use App\Models\Club;
use App\Models\YouthAcademy;
use App\Models\YouthAcademyLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YouthAcademyFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_youth_academy_with_valid_data(): void
    {
        $academy = YouthAcademy::factory()->create();

        $this->assertNotNull($academy->club_id);
        $this->assertNotNull($academy->youth_academy_level_id);
        $this->assertNotNull($academy->next_youth_player_available_at);
        $this->assertGreaterThan(0, $academy->total_investment);
    }

    public function test_it_creates_youth_academy_for_specific_club(): void
    {
        $club = Club::factory()->create();
        $academy = YouthAcademy::factory()
            ->forClub($club)
            ->create();

        $this->assertEquals($club->id, $academy->club_id);
    }

    public function test_it_creates_youth_academy_with_specific_level(): void
    {
        $level = YouthAcademyLevel::factory()->create();
        $academy = YouthAcademy::factory()
            ->withLevel($level)
            ->create();

        $this->assertEquals($level->id, $academy->youth_academy_level_id);
    }

    public function test_it_sets_next_player_date_within_30_days(): void
    {
        $academy = YouthAcademy::factory()->create();
        $now = now();
        $thirtyDaysFromNow = now()->addDays(30);

        $this->assertTrue(
            $academy->next_youth_player_available_at->between($now, $thirtyDaysFromNow)
        );
    }
}
