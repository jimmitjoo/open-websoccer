<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Player;
use App\Models\Game;
use App\Models\Injury;
use App\Models\InjuryType;
use Carbon\Carbon;

class InjuryService
{
    public function createMatchInjury(Player $player, Game $game): Injury
    {
        // Välj en slumpmässig skadetyp
        $injuryType = $this->getRandomInjuryType();

        // Beräkna återhämtningstid
        $recoveryDays = rand($injuryType->min_days, $injuryType->max_days);

        return Injury::create([
            'player_id' => $player->id,
            'injury_type_id' => $injuryType->id,
            'match_id' => $game->id,
            'started_at' => now(),
            'expected_return_at' => now()->addDays($recoveryDays)
        ]);
    }

    public function healInjury(Injury $injury): void
    {
        $injury->update([
            'actual_return_at' => now()
        ]);
    }

    public function getRandomInjuryType(): InjuryType
    {
        // Vikta mot mindre allvarliga skador
        $random = rand(1, 100);

        if ($random <= 60) { // 60% chans för mindre skador
            $severity = 'minor';
        } elseif ($random <= 90) { // 30% chans för måttliga skador
            $severity = 'moderate';
        } else { // 10% chans för allvarliga skador
            $severity = 'severe';
        }

        return InjuryType::where('severity', $severity)
            ->inRandomOrder()
            ->first();
    }
}
