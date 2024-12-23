<?php

namespace App\Providers;

use App\Models\Player;
use App\Policies\ContractPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TransferListing;
use App\Models\User;
use App\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Player::class => ContractPolicy::class,
    ];

    public function boot(): void
    {
        \Log::info('AuthServiceProvider boot start');

        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            \Log::info('Global Gate::before', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'ability' => $ability,
                'is_admin' => $user->isAdmin()
            ]);

            if ($user->isAdmin()) {
                \Log::info('Global admin bypass granted');
                return true;
            }

            return null;
        });

        \Log::info('AuthServiceProvider boot complete', [
            'registered_policies' => $this->policies,
            'gates' => Gate::abilities()
        ]);


        Gate::define('bid-on-player', function ($user, Player $player) {
            return $user->club?->id !== $player->club_id;
        });

        // Definiera Gate för att lista spelare för transfer
        Gate::define('list-for-transfer', function ($user, Player $player) {
            \Log::info('Checking list-for-transfer permission', [
                'user_id' => $user->id,
                'player_club_id' => $player->club_id,
                'user_club_id' => $user->club?->id,
                'user_role' => $user->role
            ]);

            // Kontrollera att användaren är en manager
            if ($user->role !== Role::MANAGER) {
                return false;
            }

            // Kontrollera att spelaren tillhör användarens klubb
            if ($player->club_id !== $user->club?->id) {
                return false;
            }

            // Kontrollera att spelaren inte redan är listad
            $alreadyListed = TransferListing::where('player_id', $player->id)
                ->where('status', 'active')
                ->exists();

            return !$alreadyListed;
        });
    }
}
