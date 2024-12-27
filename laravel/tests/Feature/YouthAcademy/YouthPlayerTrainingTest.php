<?php

declare(strict_types=1);

namespace Tests\Feature\YouthAcademy;

use Tests\TestCase;
use App\Models\User;
use App\Models\Club;
use App\Models\YouthPlayer;
use App\Models\YouthAcademy;
use App\Models\YouthAcademyLevel;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YouthPlayerTrainingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Club $club;
    private YouthPlayer $player;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->club = Club::factory()->create(['user_id' => $this->user->id]);
        $level = YouthAcademyLevel::factory()->withLevel(1)->create();

        $academy = YouthAcademy::create([
            'club_id' => $this->club->id,
            'youth_academy_level_id' => $level->id,
            'next_youth_player_available_at' => now(),
            'total_investment' => 0,
        ]);

        $this->player = YouthPlayer::factory()->create([
            'youth_academy_id' => $academy->id,
            'last_training_at' => null,
        ]);
    }

    public function test_player_can_be_trained_once_per_day(): void
    {
        Livewire::actingAs($this->user)
            ->test('youth-academy.player-details', ['player' => $this->player])
            ->call('train', 'strength')
            ->assertDispatched('training-success');

        $this->player->refresh();
        $this->assertNotNull($this->player->last_training_at);
    }

    public function test_player_cannot_be_trained_twice_in_same_day(): void
    {
        $this->player->update(['last_training_at' => now()]);

        Livewire::actingAs($this->user)
            ->test('youth-academy.player-details', ['player' => $this->player])
            ->call('train', 'strength')
            ->assertDispatched('training-failed')
            ->assertNotDispatched('training-success');
    }

    public function test_player_can_be_trained_again_next_day(): void
    {
        $this->player->update(['last_training_at' => now()->subDay()]);

        Livewire::actingAs($this->user)
            ->test('youth-academy.player-details', ['player' => $this->player])
            ->call('train', 'strength')
            ->assertDispatched('training-success')
            ->assertNotDispatched('training-failed');
    }
}
