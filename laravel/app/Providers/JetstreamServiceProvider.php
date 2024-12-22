<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            'manage-users',
            'manage-clubs',
            'manage-leagues',
            'manage-system',
        ])->description('Administratörer kan hantera hela systemet.');

        Jetstream::role('manager', 'Manager', [
            'manage-own-club',
            'manage-team',
            'manage-transfers',
        ])->description('Managers kan hantera sin klubb och lag.');
    }
}
