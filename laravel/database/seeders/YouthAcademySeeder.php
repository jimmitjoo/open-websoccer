<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Club;
use App\Models\YouthAcademy;
use App\Models\YouthAcademyLevel;
use Illuminate\Database\Seeder;

class YouthAcademySeeder extends Seeder
{
    public function run(): void
    {
        // Skapa en ungdomsakademi för varje klubb
        Club::all()->each(function (Club $club) {
            YouthAcademy::create([
                'club_id' => $club->id,
                'youth_academy_level_id' => YouthAcademyLevel::where('level', 1)->first()->id,
                'next_youth_player_available_at' => now()->addDays(rand(0, 7)),
                'total_investment' => 0,
            ]);
        });
    }
}
