<?php

namespace App\Http\Controllers;

use App\Models\TransferListing;
use App\Models\Player;
use App\Services\TransferMarketService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class TransferMarketController extends Controller
{
    protected $transferMarketService;

    public function __construct(TransferMarketService $transferMarketService)
    {
        $this->transferMarketService = $transferMarketService;
    }

    public function index(Request $request)
    {
        $listings = TransferListing::with(['player', 'club'])
            ->where('status', 'active')
            ->when($request->position, function($query, $position) {
                return $query->whereHas('player', function($q) use ($position) {
                    $q->where('position', $position);
                });
            })
            ->when($request->max_price, function($query, $maxPrice) {
                return $query->where('asking_price', '<=', $maxPrice);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transfer-market.index', compact('listings'));
    }

    public function listPlayer(Request $request, Player $player)
    {
        Gate::authorize('list-for-transfer', $player);

        $validated = $request->validate([
            'asking_price' => 'required|integer|min:1000'
        ]);

        try {
            $listing = $this->transferMarketService->listPlayerForTransfer(
                $player,
                $validated['asking_price']
            );

            return response()->json([
                'success' => true,
                'message' => 'Spelaren har listats på transfermarknaden.',
                'listing' => $listing
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function cancelListing(TransferListing $listing)
    {
        Gate::authorize('cancel', $listing);

        try {
            $listing->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Spelaren har tagits bort från transfermarknaden.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function myListings()
    {
        $listings = TransferListing::where('club_id', auth()->user()->club->id)
            ->where('status', 'active')
            ->with(['player', 'offers' => function ($query) {
                $query->where('status', 'pending');
            }, 'offers.bidderClub'])
            ->get();

        return view('transfer-market.my-listings', compact('listings'));
    }
}
