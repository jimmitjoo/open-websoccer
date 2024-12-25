<?php

declare(strict_types=1);

namespace App\Enums;

enum GameStatus: string
{
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Schemalagd',
            self::IN_PROGRESS => 'Pågående',
            self::COMPLETED => 'Avslutad',
            self::CANCELLED => 'Inställd',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }
}
