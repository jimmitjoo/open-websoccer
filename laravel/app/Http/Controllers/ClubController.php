<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Season;
use App\Models\Game;
use App\Models\TransferHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClubController extends Controller
{
    public function chooseClub()
    {
        return view('clubs.choose');
    }

    public function becomeManager(Request $request)
    {
        $club = Club::find($request->input('club_id'));

        Gate::authorize('becomeManager', $club);

        $club->update(['user_id' => auth()->id()]);
    }

    public function clubhouse()
    {
        $club = auth()->user()->club()->with(['leagues', 'stadium'])->firstOrFail();
        return view('clubs.clubhouse', compact('club'));
    }

    public function show(Club $club)
    {
        $club->load(['leagues', 'stadium']);
        $isOwnClub = auth()->user()?->club?->id === $club->id;

        $incomingTransfers = TransferHistory::with(['player', 'fromClub'])
            ->where('to_club_id', $club->id)
            ->where('type', 'transfer')
            ->orderByDesc('created_at')
            ->get();

        $outgoingTransfers = TransferHistory::with(['player', 'toClub'])
            ->where('from_club_id', $club->id)
            ->where('type', 'transfer')
            ->orderByDesc('created_at')
            ->get();

        return view('clubs.show', compact('club', 'isOwnClub', 'incomingTransfers', 'outgoingTransfers'));
    }

    public function squad(Club $club)
    {
        $club->load(['players' => function ($query) {
            $query->with('activeContract')
                ->orderByRaw("FIELD(position, 'GK', 'DEF', 'MID', 'FWD')")
                ->orderBy('last_name');
        }]);

        $isOwnClub = auth()->user()?->club?->id === $club->id;

        return view('clubs.squad', compact('club', 'isOwnClub'));
    }

    public function matches(Club $club, Request $request)
    {
        $season = Season::find($request->query('season')) ??
            Season::where('is_active', true)->firstOrFail();

        $seasons = Season::orderBy('start_date', 'desc')->get();

        $playedMatches = Game::where(function($query) use ($club) {
                $query->where('home_club_id', $club->id)
                      ->orWhere('away_club_id', $club->id);
            })
            ->where('season_id', $season->id)
            ->where('status', 'completed')
            ->orderBy('scheduled_at', 'desc')
            ->with(['homeClub', 'awayClub'])
            ->get();

        $upcomingMatches = Game::where(function($query) use ($club) {
                $query->where('home_club_id', $club->id)
                      ->orWhere('away_club_id', $club->id);
            })
            ->where('season_id', $season->id)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_at', 'asc')
            ->with(['homeClub', 'awayClub'])
            ->get();

        $isOwnClub = auth()->check() && $club->user_id === auth()->id();

        return view('clubs.matches', compact(
            'club',
            'season',
            'seasons',
            'playedMatches',
            'upcomingMatches',
            'isOwnClub'
        ));
    }
}
