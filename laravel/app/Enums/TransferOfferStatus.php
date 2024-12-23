<?php

declare(strict_types=1);

namespace App\Enums;

enum TransferOfferStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Väntar',
            self::ACCEPTED => 'Accepterad',
            self::REJECTED => 'Avvisad',
            self::CANCELLED => 'Tillbakadragen',
            self::EXPIRED => 'Utgången'
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::ACCEPTED => 'green',
            self::REJECTED => 'red',
            self::CANCELLED => 'gray',
            self::EXPIRED => 'gray'
        };
    }
}
