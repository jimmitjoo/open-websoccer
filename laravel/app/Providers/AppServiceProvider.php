<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Components\ClubLayout;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
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
        Blade::component('club-layout', ClubLayout::class);
    }
}
