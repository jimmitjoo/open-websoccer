<?php

namespace App\Livewire;

use App\Models\Club;
use App\Models\League;
use Livewire\Component;
use Livewire\WithPagination;

class ChooseClub extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $league = '';

    public function mount()
    {
        if (auth()->user()->club()->exists()) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $leagues = League::where('is_active', true)
            ->orderBy('rank')
            ->pluck('name', 'id');

        $query = Club::query()
            ->whereNull('user_id')
            ->where('is_active', true)
            ->with(['leagues', 'stadium']);

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if ($this->league) {
            $query->whereHas('leagues', function ($q) {
                $q->where('leagues.id', $this->league);
            });
        }

        return view('livewire.choose-club', [
            'clubs' => $query->paginate(12),
            'leagues' => $leagues
        ]);
    }

    public function chooseClub(Club $club)
    {
        if ($club->user_id) {
            $this->addError('club', 'Denna klubb är tyvärr inte längre tillgänglig.');
            return;
        }

        $club->update([
            'user_id' => auth()->id()
        ]);

        session()->flash('success', 'Du är nu manager för ' . $club->name);
        return redirect()->route('dashboard');
    }
}
