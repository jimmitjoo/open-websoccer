<?php

namespace App\Http\Controllers;

use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Services\TransferMarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransferOfferController extends Controller
{
    protected $transferMarketService;

    public function __construct(TransferMarketService $transferMarketService)
    {
        $this->transferMarketService = $transferMarketService;
    }

    public function store(Request $request, TransferListing $listing)
    {
        if ($listing->club_id === auth()->user()->club->id) {
            return response()->json([
                'success' => false,
                'message' => 'Du kan inte buda på din egen spelare.'
            ], 422);
        }

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:' . $listing->asking_price]
        ]);

        try {
            $offer = $this->transferMarketService->makeOffer(
                $listing,
                auth()->user()->club,
                ['amount' => $validated['amount']]
            );

            return response()->json([
                'success' => true,
                'message' => 'Ditt bud har skickats.',
                'offer' => $offer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function accept(TransferOffer $offer)
    {
        Gate::authorize('accept', $offer);

        try {
            $this->transferMarketService->acceptOffer($offer);

            return response()->json([
                'success' => true,
                'message' => 'Budet har accepterats och transfern är genomförd.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function reject(TransferOffer $offer)
    {
        Gate::authorize('reject', $offer);

        try {
            $offer->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Budet har avslagits.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function withdraw(TransferOffer $offer)
    {
        if ($offer->bidding_club_id !== auth()->user()->club->id) {
            return response()->json([
                'success' => false,
                'message' => 'Du kan bara dra tillbaka dina egna bud.'
            ], 403);
        }

        if ($offer->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Du kan bara dra tillbaka aktiva bud.'
            ], 422);
        }

        try {
            $offer->update(['status' => 'withdrawn']);

            return response()->json([
                'success' => true,
                'message' => 'Budet har dragits tillbaka.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
