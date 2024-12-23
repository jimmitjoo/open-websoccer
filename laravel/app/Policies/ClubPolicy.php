<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Club;

class ClubPolicy
{
    public function becomeManager(User $user, Club $club)
    {
        return !$user->club && $club->user_id === null;
    }
}
