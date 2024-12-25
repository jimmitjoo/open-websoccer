<?php

declare(strict_types=1);

namespace App\Livewire\Players;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Layout;

class FormHistory extends Component
{
    public Player $player;
    public array $formHistory = [];

    public function mount(Player $player): void
    {
        $this->player = $player;
        $this->loadFormHistory();
    }

    public function loadFormHistory(): void
    {
        // Hämta de senaste 10 formuppdateringarna
        $this->formHistory = $this->player->formUpdates()
            ->latest()
            ->take(10)
            ->get()
            ->toArray();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.players.form-history');
    }
}
