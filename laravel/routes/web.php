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
use App\Http\Controllers\TransferMarketController;
use App\Http\Controllers\TransferOfferController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PlayerController;

Route::get('/', function () {
    return view('welcome');
});

// Public club routes
Route::get('/clubs/{club}', [ClubController::class, 'show'])
    ->name('clubs.show');
Route::get('/clubs/{club}/matches', [ClubController::class, 'matches'])
    ->name('clubs.matches');
Route::get('/clubs/{club}/squad', [ClubController::class, 'squad'])
    ->name('clubs.squad');

// Public league routes
Route::get('/leagues/{league}/{season?}', [LeagueController::class, 'show'])
    ->name('leagues.show');

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

    Route::post('/become-manager', [ClubController::class, 'becomeManager'])
        ->name('become-manager');

    Route::get('/free-agents', [FreeAgentController::class, 'index'])
        ->name('free-agents.index');

    Route::post('/free-agents/{player}/negotiate', [FreeAgentController::class, 'negotiate'])
        ->name('free-agents.negotiate');
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

        Route::resource('leagues', AdminLeagueController::class)->names('leagues');
        Route::resource('seasons', SeasonController::class)->names('seasons');
    });
});

Route::middleware(['auth', 'has.club'])->group(function () {
    // Klubb-relaterade routes
    Route::get('/clubhouse', [ClubController::class, 'clubhouse'])
        ->name('clubhouse');
    Route::get('/club/finance', [ClubFinanceController::class, 'index'])
        ->name('club.finance');

    // Kontraktshantering
    Route::post('/players/{player}/negotiate', [ContractController::class, 'negotiate'])
        ->name('contracts.negotiate');
    Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->name('contracts.terminate');

    // Spelare
    Route::get('/players/{player}', [PlayerController::class, 'show'])
        ->name('players.show');

    // Transfer Market Routes
    Route::get('/transfer-market', [TransferMarketController::class, 'index'])
        ->name('transfer-market.index');

    Route::post('/transfer-market/players/{player}/list', [TransferMarketController::class, 'listPlayer'])
        ->name('transfer-market.list-player');

    Route::post('/transfer-market/listings/{listing}/offers', [TransferOfferController::class, 'store'])
        ->name('transfer-offers.store');

    Route::post('/transfer-market/offers/{offer}/accept', [TransferOfferController::class, 'accept'])
        ->name('transfer-offers.accept');

    Route::post('/transfer-market/offers/{offer}/reject', [TransferOfferController::class, 'reject'])
        ->name('transfer-offers.reject');

    Route::post('/transfer-market/listings/{listing}/cancel', [TransferMarketController::class, 'cancelListing'])
        ->name('transfer-market.cancel-listing');

    Route::get('/transfer-market/my-listings', [TransferMarketController::class, 'myListings'])
        ->name('transfer-market.my-listings');

    Route::post('/transfer-market/offers/{offer}/withdraw', [TransferOfferController::class, 'withdraw'])
        ->name('transfer-market.offers.withdraw');

    // Träningsrutter
    Route::get('/training', [TrainingController::class, 'index'])
        ->name('training.index');
    Route::post('/training/schedule', [TrainingController::class, 'schedule'])
        ->name('training.schedule');
});

// Spelarform - tillgänglig för alla inloggade användare
Route::middleware(['auth'])->group(function () {
    Route::get('/players/{player}/form-history', \App\Livewire\Players\FormHistory::class)
        ->name('players.form-history');
});

// Admin-routes för formjustering
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/players/{player}/adjust-form', \App\Livewire\Admin\FormAdjustment::class)
        ->name('admin.players.adjust-form');
});

// Ungdomsakademi routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/clubs/{club}/youth-academy', [ClubController::class, 'youthAcademy'])
        ->name('youth-academy.overview');

    Route::get('/clubs/{club}/youth-academy/players/{player}', [ClubController::class, 'youthAcademyPlayer'])
        ->name('youth-academy.player.show');
});
