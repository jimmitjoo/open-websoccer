<?php

namespace App\Http\Controllers\Admin;

use App\Models\League;
use App\Models\Season;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeagueRequest;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::with(['seasons', 'clubs'])->paginate(10);
        return view('admin.leagues.index', compact('leagues'));
    }

    public function create()
    {
        $seasons = Season::orderBy('start_date', 'desc')->get();
        return view('admin.leagues.create', compact('seasons'));
    }

    public function store(StoreLeagueRequest $request)
    {
        $league = League::create([
            'name' => $request->name,
            'country_code' => strtoupper($request->country_code),
            'level' => $request->level,
            'rank' => $request->rank,
            'max_teams' => $request->max_teams,
            'has_relegation' => $request->boolean('has_relegation'),
            'has_promotion' => $request->boolean('has_promotion'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->has('seasons')) {
            $seasons = Season::whereIn('id', $request->seasons)->get();

            $seasonData = $seasons->mapWithKeys(function ($season) {
                return [$season->id => [
                    'start_date' => $season->start_date,
                    'end_date' => $season->end_date,
                    'is_completed' => false
                ]];
            })->all();

            $league->seasons()->attach($seasonData);
        }

        return redirect()->route('admin.leagues.index')
            ->with('success', __('Ligan har skapats.'));
    }

    public function show(League $league)
    {
        $league->load(['seasons', 'clubs']);
        return view('admin.leagues.show', compact('league'));
    }

    public function edit(League $league)
    {
        $seasons = Season::orderBy('start_date', 'desc')->get();
        $league->load('seasons');
        return view('admin.leagues.edit', compact('league', 'seasons'));
    }

    public function update(StoreLeagueRequest $request, League $league)
    {
        $league->update([
            'name' => $request->name,
            'country_code' => strtoupper($request->country_code),
            'level' => $request->level,
            'rank' => $request->rank,
            'max_teams' => $request->max_teams,
            'has_relegation' => $request->boolean('has_relegation'),
            'has_promotion' => $request->boolean('has_promotion'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->has('seasons')) {
            $seasons = Season::whereIn('id', $request->seasons)->get();

            $seasonData = $seasons->mapWithKeys(function ($season) {
                return [$season->id => [
                    'start_date' => $season->start_date,
                    'end_date' => $season->end_date,
                    'is_completed' => false
                ]];
            })->all();

            $league->seasons()->sync($seasonData);
        }

        return redirect()->route('admin.leagues.index')
            ->with('success', __('Ligan har uppdaterats.'));
    }

    public function destroy(League $league)
    {
        $league->delete();
        return redirect()->route('admin.leagues.index')
            ->with('success', 'Liga borttagen framgångsrikt');
    }
}
