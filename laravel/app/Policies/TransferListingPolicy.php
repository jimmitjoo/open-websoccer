<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransferListing;

class TransferListingPolicy
{
    public function cancel(User $user, TransferListing $listing): bool
    {
        return $user->club?->id === $listing->club_id;
    }

    public function listForTransfer(User $user, Player $player): bool
    {
        return $user->club?->id === $player->club_id;
    }

    public function bidOnListedPlayer(User $user, TransferListing $listing): bool
    {
        return $user->club?->id !== $listing->player->club_id;
    }
}
