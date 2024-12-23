<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        return view('admin.seasons.create');
    }

    public function store(StoreSeasonRequest $request)
    {
        $season = Season::create($request->validated());
        return redirect()->route('admin.seasons.index')
            ->with('success', 'Säsong skapad framgångsrikt');
    }

    public function show(Season $season)
    {
        return view('admin.seasons.show', compact('season'));
    }

    public function edit(Season $season)
    {
        return view('admin.seasons.edit', compact('season'));
    }

    public function update(StoreSeasonRequest $request, Season $season)
    {
        $season->update($request->validated());
        return redirect()->route('admin.seasons.index')
            ->with('success', 'Säsong uppdaterad framgångsrikt');
    }

    public function destroy(Season $season)
    {
        $season->delete();
        return redirect()->route('admin.seasons.index')
            ->with('success', 'Säsong borttagen framgångsrikt');
    }
}
