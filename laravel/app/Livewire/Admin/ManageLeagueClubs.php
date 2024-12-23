<?php

namespace App\Livewire\Admin;

use App\Models\Club;
use App\Models\League;
use App\Models\Season;
use Livewire\Component;
use Livewire\WithPagination;

class ManageLeagueClubs extends Component
{
    use WithPagination;

    public League $league;
    public $selectedSeason = null;
    public $availableClubs = [];
    public $selectedClubs = [];

    public function mount(League $league)
    {
        $this->league = $league;
        $this->selectedSeason = $league->seasons->first()?->id;
        $this->loadClubs();
    }

    public function loadClubs()
    {
        if ($this->selectedSeason) {
            $this->availableClubs = Club::whereDoesntHave('leagues', function ($query) {
                $query->where('season_id', $this->selectedSeason);
            })->get();

            $this->selectedClubs = $this->league->clubs()
                ->wherePivot('season_id', $this->selectedSeason)
                ->get()
                ->pluck('id')
                ->toArray();
        }
    }

    public function updatedSelectedSeason()
    {
        $this->loadClubs();
    }

    public function addClub($clubId)
    {
        $this->league->clubs()->attach($clubId, [
            'season_id' => $this->selectedSeason,
            'matches_played' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'points' => 0,
            'current_position' => 0,
            'clean_sheets' => 0,
            'failed_to_score' => 0,
        ]);

        $this->loadClubs();
    }

    public function removeClub($clubId)
    {
        $this->league->clubs()
            ->wherePivot('season_id', $this->selectedSeason)
            ->detach($clubId);

        $this->loadClubs();
    }

    public function render()
    {
        return view('livewire.admin.manage-league-clubs', [
            'seasons' => $this->league->seasons,
        ]);
    }
}
