<?php

namespace App\Policies;

use App\Models\User;
use App\Models\YouthPlayer;

class YouthPlayerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function show(User $user, YouthPlayer $player)
    {
        return $user->club->id === $player->youthAcademy->club_id;
    }
}
