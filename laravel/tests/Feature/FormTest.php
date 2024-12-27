<?php

use App\Models\Player;
use App\Models\Game;
use App\Models\User;
use App\Models\FormUpdate;
use App\Services\FormService;
use Livewire\Livewire;
use App\Livewire\Admin\FormAdjustment;
use App\Livewire\Players\FormHistory;

beforeEach(function () {
    $this->player = Player::factory()->create([
        'form' => 50,
        'form_trend' => 0.0,
        'matches_played_recently' => 0
    ]);
});

it('update form after match', function () {
    $game = Game::factory()->create();
    $formService = app(FormService::class);

    $oldForm = $this->player->form;
    $formService->calculateMatchImpact($this->player, $game, 8.0); // Bra prestation

    expect($this->player->fresh())
        ->form->toBeGreaterThan($oldForm)
        ->form_trend->toBeGreaterThan(0);
});

it('decreases form over time for players with negative trend', function () {
    $this->player->update([
        'form_trend' => -2.0,
        'last_form_update' => now()->subDays(1)
    ]);

    $formService = app(FormService::class);
    $oldForm = $this->player->form;

    $formService->updateDailyForm($this->player);

    expect($this->player->fresh())
        ->form->toBeLessThan($oldForm)
        ->form_trend->toBeGreaterThan(-2.0); // Trenden ska ha minskat
});

it('allows admin to adjust form manually', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(FormAdjustment::class, ['player' => $this->player])
        ->set('newForm', 75)
        ->set('reason', 'Manuell justering efter skada')
        ->call('adjustForm');

    expect($this->player->fresh())
        ->form->toBe(75);

    expect(FormUpdate::where('player_id', $this->player->id)->first())
        ->old_value->toBe(50)
        ->new_value->toBe(75)
        ->reason->toBe('Manuell justering efter skada')
        ->adjusted_by->toBe($admin->id);
});

it('shows form history in chronological order', function () {
    // Create some form updates
    FormUpdate::factory()->create([
        'player_id' => $this->player->id,
        'old_value' => 50,
        'new_value' => 60,
        'created_at' => now()->subDays(2)
    ]);

    FormUpdate::factory()->create([
        'player_id' => $this->player->id,
        'old_value' => 60,
        'new_value' => 55,
        'created_at' => now()->subDay()
    ]);

    Livewire::test(FormHistory::class, ['player' => $this->player])
        ->assertSee('50 → 60')
        ->assertSee('60 → 55');
});

it('limits form to between 1 and 100', function () {
    $formService = app(FormService::class);
    $game = Game::factory()->create();

    // Testa övre gräns
    $this->player->update(['form' => 95]);
    $formService->calculateMatchImpact($this->player, $game, 10.0);
    expect($this->player->fresh()->form)->toBeLessThanOrEqual(100);

    // Testa undre gräns
    $this->player->update(['form' => 5]);
    $formService->calculateMatchImpact($this->player, $game, 1.0);
    expect($this->player->fresh()->form)->toBeGreaterThanOrEqual(1);
});

it('increases matches_played_recently after match', function () {
    $game = Game::factory()->create();
    $formService = app(FormService::class);

    expect($this->player->matches_played_recently)->toBe(0);

    $formService->calculateMatchImpact($this->player, $game, 7.0);

    expect($this->player->fresh()->matches_played_recently)->toBe(1);
});

it('decreases matches_played_recently over time', function () {
    $this->player->update([
        'matches_played_recently' => 3,
        'last_form_update' => now()->subDays(7)
    ]);

    $formService = app(FormService::class);
    $formService->updateDailyForm($this->player);

    expect($this->player->fresh()->matches_played_recently)->toBe(2);
});

it('decreases form naturally over time even with neutral trend', function () {
    $this->player->update([
        'form' => 70,
        'form_trend' => 0.0,
        'last_form_update' => now()->subDays(1)
    ]);

    $formService = app(FormService::class);
    $oldForm = $this->player->form;

    $formService->updateDailyForm($this->player);

    expect($this->player->fresh())
        ->form->toBeLessThan($oldForm);
});

it('properly converts player ratings to form changes', function () {
    $game = Game::factory()->create();
    $formService = app(FormService::class);

    // Test med olika ratings
    $testCases = [
        ['rating' => 4.0, 'shouldDecrease' => true],
        ['rating' => 6.0, 'shouldStayNeutral' => true],
        ['rating' => 8.0, 'shouldIncrease' => true],
    ];

    foreach ($testCases as $case) {
        $this->player->update(['form' => 60, 'form_trend' => 0.0]);
        $oldForm = $this->player->form;

        $formService->calculateMatchImpact($this->player, $game, $case['rating']);
        $newForm = $this->player->fresh()->form;

        if (isset($case['shouldDecrease'])) {
            expect($newForm)->toBeLessThan($oldForm);
        } elseif (isset($case['shouldStayNeutral'])) {
            // För neutral form, tillåt en liten variation på +/- 2
            expect($newForm)->toBeBetween($oldForm - 2, $oldForm + 2);
        } else {
            expect($newForm)->toBeGreaterThan($oldForm);
        }
    }
});
