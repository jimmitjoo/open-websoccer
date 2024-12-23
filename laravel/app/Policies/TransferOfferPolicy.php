<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransferOffer;
use App\Models\TransferListing;
class TransferOfferPolicy
{
    public function accept(User $user, TransferOffer $offer)
    {
        return $user->club?->id === $offer->transferListing->club_id;
    }

    public function reject(User $user, TransferOffer $offer)
    {
        return $user->club?->id === $offer->transferListing->club_id;
    }

    public function withdraw(User $user, TransferOffer $offer)
    {
        return $user->club?->id === $offer->bidding_club_id;
    }
}
