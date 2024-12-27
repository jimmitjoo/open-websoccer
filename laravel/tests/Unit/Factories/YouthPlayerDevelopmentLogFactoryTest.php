<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use Tests\TestCase;
use App\Models\YouthPlayer;
use App\Models\YouthPlayerDevelopmentLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YouthPlayerDevelopmentLogFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_development_log_with_valid_data(): void
    {
        $log = YouthPlayerDevelopmentLog::factory()->create();

        $this->assertNotNull($log->youth_player_id);
        $this->assertNotNull($log->attribute_name);
        $this->assertIsInt($log->old_value);
        $this->assertIsInt($log->new_value);
        $this->assertContains($log->development_type, ['training', 'natural', 'event', 'mentor']);
    }

    public function test_it_creates_training_development_log(): void
    {
        $log = YouthPlayerDevelopmentLog::factory()
            ->training()
            ->create();

        $this->assertEquals('training', $log->development_type);
        $this->assertEquals('Utveckling genom träning', $log->note);
    }

    public function test_it_creates_natural_development_log(): void
    {
        $log = YouthPlayerDevelopmentLog::factory()
            ->natural()
            ->create();

        $this->assertEquals('natural', $log->development_type);
        $this->assertEquals('Naturlig utveckling', $log->note);
    }

    public function test_it_creates_positive_development(): void
    {
        $log = YouthPlayerDevelopmentLog::factory()
            ->positiveChange()
            ->create();

        $this->assertGreaterThan($log->old_value, $log->new_value);
    }

    public function test_it_creates_negative_development(): void
    {
        $log = YouthPlayerDevelopmentLog::factory()
            ->negativeChange()
            ->create();

        $this->assertLessThan($log->old_value, $log->new_value);
    }

    public function test_it_respects_attribute_value_limits(): void
    {
        // Testa övre gräns
        $log = YouthPlayerDevelopmentLog::factory()->create([
            'old_value' => 98,
            'new_value' => 105, // Bör begränsas till 99
        ]);
        $this->assertLessThanOrEqual(99, $log->new_value);

        // Testa undre gräns
        $log = YouthPlayerDevelopmentLog::factory()->create([
            'old_value' => 2,
            'new_value' => -5, // Bör begränsas till 1
        ]);
        $this->assertGreaterThanOrEqual(1, $log->new_value);
    }
}
