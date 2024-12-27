<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Club;
use App\Models\YouthAcademy;
use App\Models\YouthPlayer;
use App\Models\YouthAcademyLevel;
use App\Models\YouthPlayerDevelopmentLog;
use App\Models\Player;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class YouthAcademyService
{
    public function createAcademyForClub(Club $club, YouthAcademyLevel $level): YouthAcademy
    {
        return YouthAcademy::create([
            'club_id' => $club->id,
            'youth_academy_level_id' => $level->id,
            'next_youth_player_available_at' => now()->addDays($level->youth_player_generation_rate),
            'total_investment' => 0,
        ]);
    }

    public function generateYouthPlayer(YouthAcademy $academy): ?YouthPlayer
    {
        if ($this->canGeneratePlayer($academy)) {
            $player = YouthPlayer::factory()
                ->forAcademy($academy)
                ->create();

            $academy->update([
                'next_youth_player_available_at' => now()
                    ->addDays($academy->level->youth_player_generation_rate)
            ]);

            return $player;
        }

        return null;
    }

    public function developPlayer(YouthPlayer $player, string $attribute, string $type): YouthPlayerDevelopmentLog
    {
        if ($type === 'training' && $player->last_training_at?->isToday()) {
            throw new \InvalidArgumentException('Player can only train once per day.');
        }

        $oldValue = $player->$attribute;
        $change = $this->calculateDevelopmentChange($player, $type);

        $player->$attribute = min(99, max(1, $oldValue + $change));
        $player->save();

        return YouthPlayerDevelopmentLog::create([
            'youth_player_id' => $player->id,
            'attribute_name' => $attribute,
            'old_value' => $oldValue,
            'new_value' => $player->$attribute,
            'development_type' => $type,
            'note' => $this->generateDevelopmentNote($type, $change),
        ]);
    }

    public function promoteToSeniorTeam(YouthPlayer $youthPlayer): Player
    {
        if (!$youthPlayer->promotion_available_at || $youthPlayer->promotion_available_at->isFuture()) {
            throw new \InvalidArgumentException('Player is not ready for promotion yet.');
        }

        $player = Player::create([
            'club_id' => $youthPlayer->youthAcademy->club_id,
            'first_name' => $youthPlayer->first_name,
            'last_name' => $youthPlayer->last_name,
            'birth_date' => now()->subYears($youthPlayer->age),
            'position' => $youthPlayer->preferred_position,
            'form' => 50,
            'strength' => $youthPlayer->strength,
            'speed' => $youthPlayer->speed,
            'technique' => $youthPlayer->technique,
            'passing' => $youthPlayer->passing,
            'shooting' => $youthPlayer->shooting,
            'heading' => $youthPlayer->heading,
            'tackling' => $youthPlayer->tackling,
            'ball_control' => $youthPlayer->ball_control,
            'stamina' => $youthPlayer->stamina,
            'keeper_ability' => $youthPlayer->keeper_ability,
        ]);

        Contract::create([
            'player_id' => $player->id,
            'club_id' => $player->club_id,
            'salary' => $this->calculateInitialSalary($youthPlayer),
            'start_date' => now(),
            'end_date' => now()->addYears(3),
        ]);

        $youthPlayer->delete();

        return $player;
    }

    private function canGeneratePlayer(YouthAcademy $academy): bool
    {
        $currentPlayers = $academy->youthPlayers()->count();
        return $currentPlayers < $academy->level->max_youth_players &&
            now()->gte($academy->next_youth_player_available_at);
    }

    private function calculateDevelopmentChange(YouthPlayer $player, string $type): int
    {
        $baseChange = match ($type) {
            'training' => max(1, $this->calculateTrainingChange($player)),
            'natural' => random_int(-1, 2),
            'event' => random_int(-3, 4),
            'mentor' => random_int(1, 3),
            default => 0,
        };

        $bonus = $player->youthAcademy->level->training_efficiency_bonus / 100;
        return (int) round($baseChange * (1 + $bonus));
    }

    private function calculateTrainingChange(YouthPlayer $player): int
    {
        $determination = $player->determination / 100;
        $workRate = $player->work_rate / 100;
        $baseChange = random_int(0, 2);

        return (int) round($baseChange * ($determination + $workRate) / 2);
    }

    private function generateDevelopmentNote(string $type, int $change): string
    {
        return match ($type) {
            'training' => $change > 0
                ? __('Progress through training')
                : __('Difficulties during training'),
            'natural' => $change > 0
                ? __('Natural development')
                : __('Temporary decline'),
            'event' => $change > 0
                ? __('Positive event affected development')
                : __('Negative event affected development'),
            'mentor' => __('Development through mentorship'),
            default => __('Development change'),
        };
    }

    private function calculateInitialSalary(YouthPlayer $player): int
    {
        $baseSalary = 10000; // Grundlön för ungdomsspelare
        $potentialBonus = $player->potential_rating * 100; // Bonus baserad på potential
        $currentAbilityBonus = $player->current_ability * 50; // Bonus baserad på nuvarande förmåga

        return (int) round($baseSalary + $potentialBonus + $currentAbilityBonus);
    }
}
