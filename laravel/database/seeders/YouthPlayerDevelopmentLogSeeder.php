<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\YouthPlayer;
use App\Models\YouthPlayerDevelopmentLog;
use Illuminate\Database\Seeder;

class YouthPlayerDevelopmentLogSeeder extends Seeder
{
    private array $attributes = [
        'strength', 'speed', 'technique', 'passing', 'shooting',
        'heading', 'tackling', 'ball_control', 'stamina', 'keeper_ability',
        'determination', 'work_rate', 'leadership'
    ];

    private array $developmentTypes = ['training', 'natural', 'event', 'mentor'];

    public function run(): void
    {
        YouthPlayer::all()->each(function (YouthPlayer $player) {
            // Skapa 1-5 utvecklingsloggar per spelare
            $numLogs = rand(1, 5);

            for ($i = 0; $i < $numLogs; $i++) {
                $attribute = $this->attributes[array_rand($this->attributes)];
                $type = $this->developmentTypes[array_rand($this->developmentTypes)];
                $oldValue = $player->$attribute - rand(-2, 5);

                YouthPlayerDevelopmentLog::create([
                    'youth_player_id' => $player->id,
                    'attribute_name' => $attribute,
                    'old_value' => max(1, min(99, $oldValue)),
                    'new_value' => $player->$attribute,
                    'development_type' => $type,
                    'note' => $this->generateNote($type, $player->$attribute - $oldValue),
                ]);
            }
        });
    }

    private function generateNote(string $type, int $change): string
    {
        return match ($type) {
            'training' => $change > 0
                ? 'Training showed good progress'
                : 'Training did not yield desired results',
            'natural' => $change > 0
                ? 'Natural development showed improvement'
                : 'Natural development showed slight decline',
            'event' => $change > 0
                ? 'Special event led to improvement'
                : 'Special event had negative impact',
            'mentor' => 'Mentoring had positive effect',
            default => 'Development noted',
        };
    }
}
