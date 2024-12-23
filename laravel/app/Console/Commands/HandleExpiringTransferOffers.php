<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TransferOffer;
use App\Enums\TransferOfferStatus;
use Illuminate\Console\Command;

class HandleExpiringTransferOffers extends Command
{
    protected $signature = 'transfer-offers:handle-expiring';
    protected $description = 'Uppdaterar utgångna transfer offers till expired status';

    public function handle(): void
    {
        $this->info('Hanterar utgångna transfer offers...');

        $count = TransferOffer::query()
            ->where('status', TransferOfferStatus::PENDING)
            ->where('deadline', '<', now())
            ->update(['status' => TransferOfferStatus::EXPIRED]);

        $this->info("Klart! {$count} transfer offers har markerats som utgångna.");
    }
}
