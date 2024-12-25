<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\MatchSimulator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateMatches extends Command
{
    protected $signature = 'matches:simulate {--date= : Specifikt datum (YYYY-MM-DD)}';
    protected $description = 'Simulera alla matcher för ett givet datum';

    public function handle(MatchSimulator $simulator): int
    {
        try {
            $date = $this->option('date') ?? now()->format('Y-m-d');

            $matches = Game::query()
                ->where('status', 'scheduled')
                ->whereDate('scheduled_at', '<=', $date)
                ->get();

            if ($matches->isEmpty()) {
                $this->info("Inga matcher att simulera för {$date}");
                return self::SUCCESS;
            }

            $this->info("Simulerar {$matches->count()} matcher för {$date}");
            $bar = $this->output->createProgressBar($matches->count());

            // Ta bort den yttre transaktionen
            foreach ($matches as $match) {
                $simulator->simulate($match);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('Matchsimulering klar!');

            // Visa lite statistik
            $this->newLine();
            $this->info('Matchresultat:');
            foreach ($matches as $match) {
                $this->line(
                    sprintf(
                        "%s %d - %d %s",
                        $match->homeClub->name,
                        $match->home_score,
                        $match->away_score,
                        $match->awayClub->name
                    )
                );
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Ett fel uppstod: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
