<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Season;

class LeagueController extends Controller
{
    public function show(League $league, ?Season $season = null)
    {
        // Om ingen säsong är vald, använd den aktiva eller senaste
        if (!$season->exists) {
            $season = $league->seasons()
                ->orderBy('is_active', 'desc')
                ->orderBy('start_date', 'desc')
                ->first();
        }

        $league->load([
            'seasons' => fn($query) => $query->orderBy('start_date', 'desc'),
            'clubs' => function ($query) use ($season) {
                $query->wherePivot('season_id', $season->id)
                    ->orderByPivot('points', 'desc')
                    ->orderByRaw('(goals_for - goals_against) DESC')
                    ->orderByPivot('goals_for', 'desc');
            },
            'clubs.stadium'
        ]);

        return view('leagues.show', compact('league', 'season'));
    }
}
