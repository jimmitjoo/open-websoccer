<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\FormService;
use Illuminate\Console\Command;

class UpdatePlayerForms extends Command
{
    protected $signature = 'players:update-forms';
    protected $description = 'Uppdaterar alla spelares form dagligen';

    public function handle(FormService $formService): int
    {
        try {
            $players = Player::all();
            $bar = $this->output->createProgressBar(count($players));

            $this->info('Startar daglig formuppdatering...');
            $bar->start();

            foreach ($players as $player) {
                $formService->updateDailyForm($player);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('Formuppdatering slutförd!');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Ett fel uppstod: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
