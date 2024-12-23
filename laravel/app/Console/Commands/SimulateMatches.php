<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\MatchSimulator;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateMatches extends Command
{
    protected $signature = 'matches:simulate';
    protected $description = 'Simulerar matcher som ska spelas nu';

    public function handle(MatchSimulator $simulator): int
    {
        DB::beginTransaction();

        try {
            // Hämta matcher som ska spelas nu (scheduled_at <= nu OCH status = scheduled)
            $matches = Game::where('status', 'scheduled')
                ->where('scheduled_at', '<=', Carbon::now())
                ->with(['homeClub', 'awayClub'])
                ->get();

            if ($matches->isEmpty()) {
                $this->info('Inga matcher att simulera.');
                return self::SUCCESS;
            }

            $bar = $this->output->createProgressBar(count($matches));
            $bar->start();

            foreach ($matches as $match) {
                $this->info("\nSimulerar match: {$match->homeClub->name} vs {$match->awayClub->name}");

                $result = $simulator->simulate($match);
                $bar->advance();
            }

            $bar->finish();
            DB::commit();

            $this->newLine();
            $this->info('Matchsimulering klar!');
            return self::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Ett fel uppstod: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
