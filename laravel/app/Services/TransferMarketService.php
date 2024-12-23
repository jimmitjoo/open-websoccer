<?php

namespace App\Services;

use App\Models\Player;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Models\Club;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ClubTransaction;
use App\Models\TransferHistory;

class TransferMarketService
{
    public function listPlayerForTransfer(Player $player, int $askingPrice): TransferListing
    {
        if (!$player->club_id) {
            throw new \Exception('Spelaren måste tillhöra en klubb för att listas på transfermarknaden.');
        }

        if (!$player->activeContract) {
            throw new \Exception('Spelaren måste ha ett aktivt kontrakt för att listas på transfermarknaden.');
        }

        // Kolla om spelaren redan är listad
        $existingListing = TransferListing::where('player_id', $player->id)
            ->where('status', 'active')
            ->first();

        if ($existingListing) {
            throw new \Exception('Spelaren är redan listad på transfermarknaden.');
        }

        return TransferListing::create([
            'player_id' => $player->id,
            'club_id' => $player->club_id,
            'asking_price' => $askingPrice,
            'status' => 'active',
            'deadline' => Carbon::now()->addDays(30)
        ]);
    }

    public function makeOffer(TransferListing $listing, Club $biddingClub, array $data): TransferOffer
    {
        // Validera att klubben har råd
        if ($data['amount'] > $biddingClub->balance) {
            throw new \Exception('Klubben har inte råd med denna transfer.');
        }

        return $listing->offers()->create([
            'bidding_club_id' => $biddingClub->id,
            'amount' => $data['amount'],
            'status' => 'pending'
        ]);
    }

    private function validateExchangePlayer(int $playerId, Club $club): void
    {
        $player = Player::findOrFail($playerId);

        if ($player->club_id !== $club->id) {
            throw new \Exception('Utbytesspelaren måste tillhöra din klubb.');
        }

        if ($player->transfer_listing) {
            throw new \Exception('Utbytesspelaren kan inte vara listad på transfermarknaden.');
        }
    }

    public function isPlayerListed(Player $player): bool
    {
        return TransferListing::where('player_id', $player->id)
            ->where('status', 'active')
            ->exists();
    }

    public function createOffer(TransferListing $listing, Club $bidderClub, int $amount): TransferOffer
    {
        if ($listing->status !== 'active') {
            throw new \Exception('Denna spelare är inte längre tillgänglig för transfer.');
        }

        if ($amount < $listing->asking_price) {
            throw new \Exception('Budet måste vara minst utgångspriset.');
        }

        if ($bidderClub->balance < $amount) {
            throw new \Exception('Din klubb har inte tillräckligt med pengar för detta bud.');
        }

        return TransferOffer::create([
            'transfer_listing_id' => $listing->id,
            'bidding_club_id' => $bidderClub->id,
            'amount' => $amount,
            'status' => 'pending'
        ]);
    }

    public function acceptOffer(TransferOffer $offer): void
    {
        DB::transaction(function () use ($offer) {
            $listing = $offer->transferListing;
            $sellingClub = $listing->club;
            $buyingClub = $offer->bidderClub;
            $player = $listing->player;

            if ($offer->amount > $buyingClub->balance) {
                throw new \Exception('Köpande klubb har inte längre råd med denna transfer.');
            }
            //string $description, float $amount, string $type
            $buyingClub->addTransaction('Transfer av ' . $player->first_name . ' ' . $player->last_name, $offer->amount, 'expense');
            $sellingClub->addTransaction('Transfer av ' . $player->first_name . ' ' . $player->last_name, $offer->amount, 'income');

            $player->update(['club_id' => $buyingClub->id]);

            // Avsluta nuvarande kontrakt
            $currentContract = $player->contracts()
                ->where('club_id', $sellingClub->id)
                ->where('end_date', '>', now())
                ->first();

            $currentContract->update(['status' => 'terminated', 'end_date' => now()]);

            // Skapa nytt 3-månaders kontrakt med nya klubben
            Contract::create([
                'player_id' => $player->id,
                'club_id' => $buyingClub->id,
                'salary' => $currentContract->salary,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
            ]);

            // Skapa en transer history entry
            TransferHistory::create([
                'player_id' => $player->id,
                'from_club_id' => $sellingClub->id,
                'to_club_id' => $buyingClub->id,
                'amount' => $offer->amount,
                'fee' => $offer->amount,
                'type' => 'transfer',
            ]);

            $offer->update(['status' => 'accepted']);
            $listing->offers()
                ->where('id', '!=', $offer->id)
                ->update(['status' => 'rejected']);

            $listing->update(['status' => 'completed']);
        });
    }
}
