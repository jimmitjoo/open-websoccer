<?php

declare(strict_types=1);

namespace App\Enums;

enum TransferListingStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktiv',
            self::COMPLETED => 'Genomförd',
            self::CANCELLED => 'Avbruten',
            self::EXPIRED => 'Utgången'
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::COMPLETED => 'blue',
            self::CANCELLED => 'red',
            self::EXPIRED => 'gray'
        };
    }
}
