<?php

declare(strict_types=1);

namespace App\Livewire\YouthAcademy;

use App\Models\Club;
use App\Models\YouthAcademy;
use App\Services\YouthAcademyService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\YouthAcademyLevel;

#[Layout('layouts.app')]
#[Title('Ungdomsakademi')]
class Overview extends Component
{
    public Club $club;
    public YouthAcademy $academy;
    public float $upgradeCost = 0;

    public function mount(Club $club): void
    {
        $this->club = $club;
        $this->academy = $club->youthAcademy;
        $this->loadUpgradeCosts();
    }

    public function generatePlayer(): void
    {
        $service = app(YouthAcademyService::class);
        $player = $service->generateYouthPlayer($this->academy);

        if ($player) {
            $this->dispatch('player-generated', 'New youth player generated!');
        } else {
            $this->dispatch('generation-failed', 'Could not generate new player at this time.');
        }
    }

    public function upgradeAcademy(): void
    {
        if ($this->club->balance < $this->upgradeCost) {
            $this->dispatch('upgrade-failed', 'Insufficient funds for upgrade.');
            return;
        }

        $nextLevel = $this->academy->level->level + 1;
        $nextLevelModel = YouthAcademyLevel::where('level', $nextLevel)->first();

        if (!$nextLevelModel) {
            $this->dispatch('upgrade-failed', 'Maximum level reached.');
            return;
        }

        try {
            $this->club->decrement('balance', $this->upgradeCost);
            $this->academy->increment('total_investment', $this->upgradeCost);
            $this->academy->update([
                'youth_academy_level_id' => $nextLevelModel->id
            ]);

            $this->academy->refresh();
            $this->loadUpgradeCosts();

            $this->dispatch('upgrade-success', 'Youth academy upgraded successfully!');
        } catch (\Exception $e) {
            $this->dispatch('upgrade-failed', 'Failed to upgrade youth academy.');
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.youth-academy.overview', [
            'youthPlayers' => $this->academy->youthPlayers()
                ->with('developmentLogs')
                ->latest()
                ->paginate(10),
        ]);
    }

    private function loadUpgradeCosts(): void
    {
        $nextLevel = $this->academy->level->level + 1;
        $this->upgradeCost = (float) YouthAcademyLevel::where('level', $nextLevel)
            ->value('upgrade_cost') ?? 0;
    }
}
