<?php

namespace App\Http\Controllers;

use App\Models\Club;

class ClubController extends Controller
{
    public function chooseClub()
    {
        return view('clubs.choose');
    }

    public function clubhouse()
    {
        $club = auth()->user()->club()->with(['leagues', 'stadium'])->firstOrFail();
        return view('clubs.clubhouse', compact('club'));
    }

    public function show(Club $club)
    {
        $club->load(['leagues', 'stadium']);
        $isOwnClub = auth()->user()->club?->id === $club->id;

        return view('clubs.show', compact('club', 'isOwnClub'));
    }

    public function squad(Club $club)
    {
        $club->load(['players' => function ($query) {
            $query->with('activeContract')
                ->orderByRaw("FIELD(position, 'GK', 'DEF', 'MID', 'FWD')")
                ->orderBy('last_name');
        }]);

        $isOwnClub = auth()->user()->club?->id === $club->id;

        return view('clubs.squad', compact('club', 'isOwnClub'));
    }
}
