<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\YouthAcademy;
use App\Models\YouthPlayer;
use Illuminate\Database\Seeder;

class YouthPlayerSeeder extends Seeder
{
    public function run(): void
    {
        YouthAcademy::all()->each(function (YouthAcademy $academy) {
            $maxPlayers = $academy->level->max_youth_players;
            $numPlayers = rand(1, $maxPlayers);

            YouthPlayer::factory()
                ->count($numPlayers)
                ->create([
                    'youth_academy_id' => $academy->id,
                    'promotion_available_at' => rand(0, 1) ? now()->addDays(rand(30, 180)) : null,
                ]);
        });
    }
}
