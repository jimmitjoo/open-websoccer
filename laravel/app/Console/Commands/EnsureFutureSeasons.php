<?php

namespace App\Console\Commands;

use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnsureFutureSeasons extends Command
{
    protected $signature = 'seasons:ensure-future';
    protected $description = 'Säkerställer att det finns minst 10 framtida säsonger';

    public function handle(): void
    {
        $this->info('Kontrollerar framtida säsonger...');

        // Hämta senaste säsongen
        $latestSeason = Season::orderBy('end_date', 'desc')->first();

        if (!$latestSeason) {
            $this->createInitialSeasons();
            return;
        }

        // Räkna framtida säsonger
        $futureSeasons = Season::where('start_date', '>', now())->count();

        $needed = 10 - $futureSeasons;
        if ($needed <= 0) {
            $this->info('Det finns redan tillräckligt med framtida säsonger.');
            return;
        }

        // Skapa nya säsonger
        $lastDate = $latestSeason->end_date;
        for ($i = 0; $i < $needed; $i++) {
            $startDate = Carbon::parse($lastDate)->addDay();
            $endDate = Carbon::parse($startDate)->addMonths(3)->subDay();

            $year = $startDate->year;
            $quarter = ceil($startDate->month / 3);

            Season::create([
                'name' => "{$year}-{$quarter}",
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => false,
            ]);

            $lastDate = $endDate;
            $this->info("Skapade säsong {$year}-{$quarter}");
        }

        $this->info("Klart! Skapade {$needed} nya säsonger.");
    }

    private function createInitialSeasons(): void
    {
        $this->info('Inga säsonger hittades. Skapar initiala säsonger...');

        $startDate = now()->startOfQuarter();

        for ($i = 0; $i < 10; $i++) {
            $endDate = Carbon::parse($startDate)->addMonths(3)->subDay();

            $year = $startDate->year;
            $quarter = ceil($startDate->month / 3);

            Season::create([
                'name' => "{$year}-{$quarter}",
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => $i === 0, // Första säsongen är aktiv
            ]);

            $startDate = $endDate->addDay();
            $this->info("Skapade säsong {$year}-{$quarter}");
        }
    }
}
