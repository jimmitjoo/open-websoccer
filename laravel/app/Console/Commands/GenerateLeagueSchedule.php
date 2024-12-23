<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\League;
use App\Models\Season;
use App\Models\Game;
use App\Services\MatchScheduler;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateLeagueSchedule extends Command
{
    protected $signature = 'matches:schedule
                          {league? : ID för specifik liga}
                          {--season= : ID för specifik säsong}';

    protected $description = 'Genererar matchschema för ligor som saknar matcher';

    public function handle(MatchScheduler $scheduler): int
    {
        DB::beginTransaction();

        try {
            $season = $this->option('season')
                ? Season::findOrFail($this->option('season'))
                : Season::where('is_active', true)->firstOrFail();

            $query = League::query()
                ->whereHas('seasons', function ($q) use ($season) {
                    $q->where('seasons.id', $season->id);
                })
                ->whereDoesntHave('seasons', function ($q) use ($season) {
                    $q->where('seasons.id', $season->id)
                      ->whereHas('games');
                });

            if ($leagueId = $this->argument('league')) {
                $query->where('id', $leagueId);
            }

            $leagues = $query->get();

            if ($leagues->isEmpty()) {
                $this->info('Inga ligor behöver schemaläggning.');
                return self::SUCCESS;
            }

            $bar = $this->output->createProgressBar(count($leagues));
            $bar->start();

            foreach ($leagues as $league) {
                $this->info("\nGenererar schema för {$league->name}");

                $scheduler->generateSchedule($league, $season);

                $bar->advance();
            }

            $bar->finish();

            DB::commit();

            $this->newLine();
            $this->info('Matchschema har genererats klart!');

            return self::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();

            $this->error('Ett fel uppstod: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
