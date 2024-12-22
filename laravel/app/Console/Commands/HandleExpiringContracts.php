<?php

namespace App\Console\Commands;

use App\Services\ContractService;
use Illuminate\Console\Command;

class HandleExpiringContracts extends Command
{
    protected $signature = 'contracts:handle-expiring';
    protected $description = 'Kontrollera och hantera utgående kontrakt';

    public function handle(ContractService $contractService)
    {
        $this->info('Hanterar utgående kontrakt...');
        $contractService->handleExpiringContracts();
        $this->info('Klart!');
    }
}
