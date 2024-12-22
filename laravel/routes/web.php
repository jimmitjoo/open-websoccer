<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\LeagueController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Klubbval för användare utan klubb
    Route::get('/choose-club', [ClubController::class, 'chooseClub'])
        ->name('choose-club')
        ->middleware('no.club');

    // Klubbhus - endast tillgängligt för managers med klubb
    Route::get('/clubhouse', [ClubController::class, 'clubhouse'])
        ->name('clubhouse')
        ->middleware('has.club');

    // Visa andra klubbar
    Route::get('/clubs/{club}', [ClubController::class, 'show'])
        ->name('clubs.show');

    // Liga routes
    Route::get('/leagues/{league}/{season?}', [LeagueController::class, 'show'])
        ->name('leagues.show');

    // Truppvy för klubbar
    Route::get('/clubs/{club}/squad', [ClubController::class, 'squad'])
        ->name('clubs.squad');
});

Route::middleware(['auth'])->group(function () {
    Route::put('/settings', [UserSettingsController::class, 'update'])
        ->name('user.settings.update');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', function () {
        return view('admin.users');
    })->name('admin.users');
});
