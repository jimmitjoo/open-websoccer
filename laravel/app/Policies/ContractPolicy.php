<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contract;
use App\Models\Player;
use App\Models\Role;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContractPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        Log::info('ContractPolicy::before check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'ability' => $ability,
            'role_check' => Role::ADMIN,
            'role_comparison' => $user->role . ' === ' . Role::ADMIN,
            'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)
        ]);

        if ($user->isAdmin()) {
            Log::info('Admin bypass granted');
            return true;
        }

        return null;
    }

    /**
     * Determine if the given player can be negotiated with by the user.
     */
    public function negotiate(User $user, Player $player): Response
    {
        Log::info('ContractPolicy::negotiate check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_club_id' => $user->club?->id,
            'player_club_id' => $player->club_id,
            'has_club' => (bool) $user->club,
            'club_match' => $user->club?->id === $player->club_id,
            'is_admin' => $user->isAdmin(),
            'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)
        ]);

        if (!$user->club) {
            return Response::deny('Du måste ha en klubb för att förhandla.');
        }

        if ($user->club->id !== $player->club_id) {
            return Response::deny('Du kan bara förhandla med spelare i din egen klubb.');
        }

        return Response::allow();
    }

    /**
     * Determine if the given contract can be terminated by the user.
     */
    public function terminate(User $user, Contract $contract): Response
    {
        if (!$user->club) {
            return Response::deny('Du måste ha en klubb för att avsluta kontrakt.');
        }

        if ($user->club->id !== $contract->club_id) {
            return Response::deny('Du kan bara avsluta kontrakt i din egen klubb.');
        }

        // Beräkna uppsägningskostnad
        $remainingMonths = Carbon::now()->diffInMonths($contract->end_date);
        $terminationCost = max(
            $contract->salary * 3,
            ($contract->salary * $remainingMonths) * 0.5
        );

        if ($user->club->balance < $terminationCost) {
            return Response::deny(
                'Din klubb har inte råd att avsluta kontraktet. Det skulle kosta ' .
                number_format($terminationCost) . ' kr.'
            );
        }

        return Response::allow();
    }
}
