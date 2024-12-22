<?php

namespace App\Http\Controllers;

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
}
