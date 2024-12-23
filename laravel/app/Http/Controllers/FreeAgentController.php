<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\ContractService;
use Illuminate\Http\Request;

class FreeAgentController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    public function index()
    {
        $freeAgents = Player::whereNull('club_id')
            ->orderBy('position')
            ->orderBy('last_name')
            ->get();

        return view('free-agents.index', compact('freeAgents'));
    }

    public function negotiate(Request $request, Player $player)
    {
        try {
            $validated = $request->validate([
                'salary' => 'required|numeric|min:1000',
                'duration' => 'required|integer|min:1|max:60',
            ]);

            if ($player->club_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Denna spelare tillhör redan en klubb.'
                ], 422);
            }

            $user = $request->user();
            $club = $user->club;

            \Log::info('Free agent negotiation user check', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'has_club' => (bool) $club,
                'club_details' => $club ? [
                    'id' => $club->id,
                    'name' => $club->name,
                    'user_id' => $club->user_id
                ] : null
            ]);

            if (!$club) {
                return response()->json([
                    'success' => false,
                    'message' => 'Du måste ha en klubb för att förhandla med spelare.'
                ], 422);
            }

            \Log::info('Free agent negotiation attempt', [
                'player_id' => $player->id,
                'club_id' => $club->id,
                'current_club_id' => $player->club_id,
                'offered_salary' => $validated['salary'],
                'offered_duration' => $validated['duration']
            ]);

            // Uppdatera club_id och spara direkt
            $player->club_id = $club->id;
            $player->save();

            $accepted = $this->contractService->negotiateNewContract($player, $validated);

            if (!$accepted) {
                $player->club_id = null;
                $player->save();
            }

            return response()->json([
                'success' => true,
                'accepted' => $accepted,
                'message' => $accepted ? 'Spelaren accepterade kontraktet!' : 'Spelaren avböjde erbjudandet.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Free agent negotiation error', [
                'player_id' => $player->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($player)) {
                $player->club_id = null;
                $player->save();
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
