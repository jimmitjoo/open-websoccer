<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

class FormAdjustment extends Component
{
    public Player $player;

    #[Rule('required|integer|min:1|max:100')]
    public int $newForm;

    #[Rule('required|string|max:255')]
    public string $reason = '';

    public function mount(Player $player): void
    {
        $this->player = $player;
        $this->newForm = $player->form;
    }

    public function adjustForm(): void
    {
        $this->validate();

        $oldForm = $this->player->form;

        $this->player->formUpdates()->create([
            'old_value' => $oldForm,
            'new_value' => $this->newForm,
            'reason' => $this->reason,
            'adjusted_by' => auth()->id()
        ]);

        $this->player->update(['form' => $this->newForm]);

        $this->dispatch('form-adjusted', playerId: $this->player->id);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.form-adjustment');
    }
}
