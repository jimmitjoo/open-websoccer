<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TrainingSession;
use App\Services\TrainingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExecuteTrainingSessions extends Command
{
    protected $signature = 'training:execute';
    protected $description = 'Kör alla schemalagda träningspass för dagens datum';

    public function __construct(
        private readonly TrainingService $trainingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Söker efter träningspass att köra...');

        try {
            $sessions = TrainingSession::query()
                ->where('status', 'scheduled')
                ->where('scheduled_date', '<=', now()->toDateString())
                ->with(['trainingType', 'players', 'club'])
                ->get();

            if ($sessions->isEmpty()) {
                $this->info('Inga träningspass att köra idag.');
                return self::SUCCESS;
            }

            $bar = $this->output->createProgressBar(count($sessions));
            $bar->start();

            foreach ($sessions as $session) {
                $this->trainingService->executeTraining($session);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('Alla träningspass har körts klart!');

            return self::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Fel vid körning av träningspass: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            $this->error('Ett fel uppstod: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
