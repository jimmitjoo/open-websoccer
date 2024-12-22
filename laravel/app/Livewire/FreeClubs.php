<?php

namespace App\Livewire;

use App\Models\Club;
use Livewire\Component;
use Livewire\WithPagination;

class FreeClubs extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $league = null;

    public function render()
    {
        $query = Club::query()
            ->whereNull('user_id')
            ->where('is_active', true)
            ->with(['league']);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if ($this->league) {
            $query->where('league_id', $this->league);
        }

        return view('livewire.free-clubs', [
            'clubs' => $query->paginate(10)
        ]);
    }

    public function chooseClub(Club $club)
    {
        abort_unless(auth()->user()->can('choose-club'), 403);

        $club->update([
            'user_id' => auth()->id()
        ]);

        $this->dispatch('club-chosen');
    }
}
