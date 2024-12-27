<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Player;
use App\Models\Game;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormService
{
    // Konstanter för formberäkning
    private const MIN_FORM = 1;
    private const MAX_FORM = 100;
    private const DEFAULT_FORM = 60;

    // Hur många matcher som påverkar den senaste formen
    private const RECENT_MATCHES_COUNT = 5;

    public function calculateMatchImpact(Player $player, Game $game, float $rating): void
    {
        DB::transaction(function () use ($player, $game, $rating) {
            $oldForm = $player->form;

            // Uppdatera antalet spelade matcher nyligen
            $player->matches_played_recently = min(
                self::RECENT_MATCHES_COUNT,
                $player->matches_played_recently + 1
            );

            // Konvertera rating (1-10) till formskala (1-100)
            // En rating på 6.0 ska motsvara neutral form (60)
            $ratingInFormScale = ($rating - 6.0) * 20 + 60;

            // Beräkna formtrend baserat på matchbetyg
            $trendImpact = ($ratingInFormScale - 60) / 10;
            $player->form_trend = $this->calculateNewTrend($player->form_trend, $trendImpact);

            // Applicera trenden på formen
            $formChange = $this->calculateFormChange($player->form_trend, $player->matches_played_recently);
            $player->form = $this->adjustForm($player->form + $formChange);

            $player->last_form_update = now();

            // Spara form_update
            $player->formUpdates()->create([
                'old_value' => $oldForm,
                'new_value' => $player->form,
                'match_id' => $game->id,
                'reason' => "Match performance: Rating {$rating}"
            ]);

            $player->save();

            Log::info('Spelarform uppdaterad efter match', [
                'player_id' => $player->id,
                'match_id' => $game->id,
                'rating' => $rating,
                'old_form' => $oldForm,
                'new_form' => $player->form,
                'trend' => $player->form_trend
            ]);
        });
    }

    public function updateDailyForm(Player $player): void
    {
        DB::transaction(function () use ($player) {
            $oldForm = $player->form;

            // Minska trendeffekten över tid
            $player->form_trend *= 0.9;

            // Lägg till en naturlig formförsämring
            $naturalDecay = -1; // -1 poäng per dag

            // Applicera en mindre formförändring baserad på trenden plus naturlig försämring
            $formChange = (int) round($this->calculateFormChange($player->form_trend, $player->matches_played_recently) * 0.5) + $naturalDecay;
            $player->form = $this->adjustForm($player->form + $formChange);

            // Minska antalet nyligen spelade matcher över tid
            if ($player->matches_played_recently > 0 && $player->last_form_update?->diffInDays(now()) >= 7) {
                $player->matches_played_recently--;
            }

            $player->last_form_update = now();

            // Spara form_update om formen ändrades
            if ($oldForm !== $player->form) {
                $player->formUpdates()->create([
                    'old_value' => $oldForm,
                    'new_value' => $player->form,
                    'reason' => 'Daily form update'
                ]);
            }

            $player->save();
        });
    }

    private function calculateNewTrend(float $currentTrend, float $impact): float
    {
        // Begränsa trenden till -5.0 till +5.0
        return max(-5.0, min(5.0, ($currentTrend * 0.7) + ($impact * 0.3)));
    }

    private function calculateFormChange(float $trend, int $recentMatches): int
    {
        // Större förändringar om spelaren har spelat många matcher nyligen
        $matchMultiplier = (1 + ($recentMatches / self::RECENT_MATCHES_COUNT)) / 2;
        // Konvertera trenden till formpoäng (1-100 skala)
        return (int) round($trend * $matchMultiplier * 10);
    }

    private function adjustForm(int $form): int
    {
        return max(self::MIN_FORM, min(self::MAX_FORM, $form));
    }
}
