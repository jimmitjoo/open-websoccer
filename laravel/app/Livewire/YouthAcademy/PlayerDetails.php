<?php

declare(strict_types=1);

namespace App\Livewire\YouthAcademy;

use App\Models\YouthPlayer;
use App\Services\YouthAcademyService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\YouthAcademyLevel;

#[Layout('layouts.app')]
#[Title('Ungdomsspelare')]
class PlayerDetails extends Component
{
    public YouthPlayer $player;
    public array $playerAttributes = [
        'strength' => 'Strength',
        'speed' => 'Speed',
        'technique' => 'Technique',
        'passing' => 'Passing',
        'shooting' => 'Shooting',
        'heading' => 'Heading',
        'tackling' => 'Tackling',
        'ball_control' => 'Ball Control',
        'stamina' => 'Stamina',
        'keeper_ability' => 'Goalkeeper Ability',
    ];

    public array $personalityAttributes = [
        'determination' => 'Determination',
        'work_rate' => 'Work Rate',
        'leadership' => 'Leadership',
    ];

    public function train(string $attribute): void
    {
        $service = app(YouthAcademyService::class);
        try {
            $log = $service->developPlayer($this->player, $attribute, 'training');
            $this->player->update(['last_training_at' => now()]);

            if ($log->new_value > $log->old_value) {
                $this->dispatch('training-success', 'Training showed positive results!');
            } else {
                $this->dispatch('training-failed', 'Training did not yield desired results this time.');
            }
        } catch (\InvalidArgumentException $e) {
            $this->dispatch('training-failed', $e->getMessage());
        }

        $this->player->refresh();
    }

    public function promotePlayer(): void
    {
        try {
            $service = app(YouthAcademyService::class);
            $seniorPlayer = $service->promoteToSeniorTeam($this->player);

            $this->dispatch('promotion-success',
                'Spelaren har flyttats upp till A-laget!',
                ['redirect' => route('players.show', $seniorPlayer)]
            );
        } catch (\Exception $e) {
            \Log::error('Failed to promote player: ' . $e->getMessage());
            $this->dispatch('promotion-failed', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.youth-academy.player-details', [
            'developmentLogs' => $this->player->developmentLogs()
                ->with('youthPlayer')
                ->latest()
                ->paginate(10),
        ]);
    }
}
