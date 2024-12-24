<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TrainingType;
use App\Models\TrainingSession;
use App\Services\TrainingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class TrainingController extends Controller
{
    public function __construct(
        private readonly TrainingService $trainingService
    ) {}

    public function index(Request $request)
    {
        $club = $request->user()->club;

        $sessions = TrainingSession::query()
            ->where('club_id', $club->id)
            ->with(['trainingType', 'players'])
            ->orderBy('scheduled_date', 'desc')
            ->paginate(10);

        $trainingTypes = TrainingType::all();

        return view('training.index', compact('sessions', 'trainingTypes'));
    }

    public function schedule(Request $request)
    {
        $validated = $request->validate([
            'training_type_id' => 'required|exists:training_types,id',
            'date' => 'required|date|after:today',
            'player_ids' => [
                'required',
                'array',
                Rule::exists('players', 'id')
                    ->where(function ($query) use ($request) {
                        $query->where('club_id', $request->user()->club->id);
                    })
            ]
        ]);

        $validated['club_id'] = $request->user()->club->id;

        $this->trainingService->scheduleTraining($validated);

        return redirect()
            ->route('training.index')
            ->with('success', 'Träningspass schemalagt!');
    }
}
