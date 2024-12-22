<?php

namespace App\Providers;

use App\Models\Player;
use App\Policies\ContractPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
    }
}
