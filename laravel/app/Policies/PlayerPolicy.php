<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Role;

class PlayerPolicy
{
    use HandlesAuthorization;

    public function adjustForm(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->role == Role::MANAGER;
    }
}
