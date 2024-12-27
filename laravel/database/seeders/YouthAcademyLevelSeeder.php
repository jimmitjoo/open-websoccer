<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\YouthAcademyLevel;
use Illuminate\Database\Seeder;

class YouthAcademyLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            'Basic Training Ground',
            'Local Youth Setup',
            'Youth Development Center',
            'Regional Academy',
            'Professional Academy',
            'Elite Academy',
            'Advanced Training Complex',
            'National Academy',
            'International Academy',
            'World Class Academy',
            'Elite Development Hub',
            'Global Talent Center',
            'Premier Academy',
            'Master Class Facility',
            'Ultimate Training Center',
            'Legendary Academy',
            'Supreme Development Center',
            'Perfect Training Complex',
            'Total Football Academy',
            'Elite Football University'
        ];

        foreach ($levels as $index => $name) {
            $level = $index + 1;
            $baseMonthly = 50000;
            $baseUpgrade = 500000;

            YouthAcademyLevel::create([
                'name' => $name,
                'level' => $level,
                'monthly_cost' => $baseMonthly * pow(1.2, $level - 1),
                'upgrade_cost' => $baseUpgrade * pow(1.5, $level - 1),
                'max_youth_players' => min(5 + floor($level / 2), 20),
                'youth_player_generation_rate' => max(30 - $level, 7), // Från 30 till 7 dagar
                'min_potential_rating' => min(40 + $level * 2, 70),
                'max_potential_rating' => min(60 + $level * 2, 99),
                'training_efficiency_bonus' => $level * 5, // 5% bonus per nivå
            ]);
        }
    }
}
