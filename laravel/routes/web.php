<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ClubFinanceController;
use App\Http\Controllers\FreeAgentController;
use App\Http\Controllers\Admin\LeagueController as AdminLeagueController;
use App\Http\Controllers\Admin\SeasonController;

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

    // Klubbens matcher
    Route::get('/clubs/{club}/matches', [ClubController::class, 'matches'])
        ->name('clubs.matches');

    // Truppvy för klubbar
    Route::get('/clubs/{club}/squad', [ClubController::class, 'squad'])
        ->name('clubs.squad');

    // Liga routes
    Route::get('/leagues/{league}/{season?}', [LeagueController::class, 'show'])
        ->name('leagues.show');

    // Klubb-relaterade routes
    Route::get('/clubhouse', [ClubController::class, 'clubhouse'])->name('clubhouse')->middleware('has.club');
    Route::get('/club/finance', [ClubFinanceController::class, 'index'])->name('club.finance')->middleware('has.club');

    Route::get('/free-agents', [FreeAgentController::class, 'index'])->name('free-agents.index');
    Route::post('/free-agents/{player}/negotiate', [FreeAgentController::class, 'negotiate'])->name('free-agents.negotiate');
});

Route::middleware(['auth'])->group(function () {
    Route::put('/settings', [UserSettingsController::class, 'update'])
        ->name('user.settings.update');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');

        Route::resource('leagues', AdminLeagueController::class);
        Route::resource('seasons', SeasonController::class);

        Route::post('leagues/{league}/clubs', [LeagueClubController::class, 'store'])
            ->name('leagues.clubs.store');
    });
});

// Kontraktshantering
Route::middleware(['auth', 'has.club'])->group(function () {
    Route::post('/players/{player}/negotiate', [ContractController::class, 'negotiate'])
        ->name('contracts.negotiate');
    Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->name('contracts.terminate');
    Route::get('/clubfinance', [ClubFinanceController::class, 'index'])->name('club.finance');
});
