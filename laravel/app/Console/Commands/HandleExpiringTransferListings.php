<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TransferListing;
use App\Enums\TransferListingStatus;
use Illuminate\Console\Command;

class HandleExpiringTransferListings extends Command
{
    protected $signature = 'transfer-listings:handle-expiring';
    protected $description = 'Uppdaterar utgångna transfer listings till expired status';

    public function handle(): void
    {
        $this->info('Hanterar utgångna transfer listings...');

        $count = TransferListing::query()
            ->where('status', TransferListingStatus::ACTIVE)
            ->where('deadline', '<', now())
            ->update(['status' => TransferListingStatus::EXPIRED]);

        $this->info("Klart! {$count} transfer listings har markerats som utgångna.");
    }
}
