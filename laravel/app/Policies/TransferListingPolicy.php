<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransferListing;

class TransferListingPolicy
{
    public function cancel(User $user, TransferListing $listing): bool
    {
        return $user->club_id === $listing->club_id;
    }

    public function listForTransfer(User $user, Player $player): bool
    {
        return $user->club_id === $player->club_id;
    }
}
