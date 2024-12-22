<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    public function negotiate(Request $request, Player $player)
    {
        try {
            $user = $request->user();

            \Log::info('Contract negotiation attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_club_id' => $user->club?->id,
                'player_id' => $player->id,
                'player_club_id' => $player->club_id,
                'is_admin' => $user->isAdmin(),
                'request_data' => $request->all()
            ]);

            if (!Gate::allows('negotiate', $player)) {
                \Log::warning('Contract negotiation denied', [
                    'user_id' => $user->id,
                    'player_id' => $player->id,
                    'user_club_id' => $user->club?->id,
                    'player_club_id' => $player->club_id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Du har inte behörighet att förhandla med denna spelare.'
                ], 403);
            }

            $validated = $request->validate([
                'salary' => 'required|numeric|min:1000',
                'duration' => 'required|integer|min:1|max:60',
            ]);

            $accepted = $this->contractService->negotiateNewContract($player, $validated);

            return response()->json([
                'success' => true,
                'accepted' => $accepted,
                'message' => $accepted ? 'Spelaren accepterade kontraktet!' : 'Spelaren avböjde erbjudandet.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Contract negotiation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function terminate(Contract $contract)
    {
        if (!request()->user()->can('terminate', $contract)) {
            abort(403, 'Du har inte behörighet att avsluta detta kontrakt.');
        }

        try {
            $this->contractService->terminateContract($contract);

            return response()->json([
                'success' => true,
                'message' => 'Kontraktet har avslutats'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
