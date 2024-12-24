<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TrainingSession;
use App\Models\Player;
use App\Models\TrainingType;
use Illuminate\Support\Facades\DB;

class TrainingService
{
    public function scheduleTraining(array $data): TrainingSession
    {
        return DB::transaction(function () use ($data) {
            $session = TrainingSession::create([
                'club_id' => $data['club_id'],
                'training_type_id' => $data['training_type_id'],
                'scheduled_date' => $data['date']
            ]);

            // Koppla spelare till träningspasset
            $session->players()->attach($data['player_ids']);

            return $session;
        });
    }

    public function executeTraining(TrainingSession $session): void
    {
        DB::transaction(function () use ($session) {
            foreach ($session->players as $player) {
                $effects = $this->calculateTrainingEffects(
                    $player,
                    $session->trainingType
                );

                $this->applyTrainingEffects($player, $effects);

                // Spara vilka effekter som applicerades
                $session->players()->updateExistingPivot($player->id, [
                    'effects_applied' => $effects
                ]);
            }

            $session->update(['status' => 'completed']);
        });
    }

    private function calculateTrainingEffects(Player $player, TrainingType $type): array
    {
        $effects = $type->effects;

        // Justera effekter baserat på spelarens tillstånd
        if ($player->is_injured) {
            $effects = $this->adjustEffectsForInjury($effects);
        }

        // Lägg till slumpmässig variation (±20%)
        foreach ($effects as $attribute => $value) {
            $variation = $value * (rand(-20, 20) / 100);
            $effects[$attribute] = $value + $variation;
        }

        return $effects;
    }

    private function applyTrainingEffects(Player $player, array $effects): void
    {
        foreach ($effects as $attribute => $change) {

            if ($player->injured && $change > 0) {
                $change = round($change * 0.25);
            } else {
                $change = round($change);
            }

            // Applicera förändringen om attributet finns
            if (isset($player->$attribute)) {

                $newValue = $player->$attribute + $change;

                // Säkerställ att värdet är inom tillåtet intervall (1-100)
                $player->$attribute = min(100, max(0, $newValue));
            }
        }

        // Uppdatera spelarens form baserat på träningseffekterna
        $maxMin = $player->form;
        $formChange = rand(max(-50, $maxMin), 50);
        $player->form = min(100, $player->form + $formChange);

        $player->save();
    }

    private function adjustEffectsForInjury(array $effects): array
    {
        // Reducera alla positiva effekter med 75% för skadade spelare
        foreach ($effects as $attribute => $value) {
            if ($value > 0) {
                $effects[$attribute] = $value * 0.25;
            }
        }

        return $effects;
    }
}
