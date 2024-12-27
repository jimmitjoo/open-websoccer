<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class RatingBadge extends Component
{
    public function __construct(
        public int $value,
        public ?string $label = null,
        public string $size = 'md'
    ) {}

    public function render()
    {
        return view('components.rating-badge', [
            'color' => $this->getColor(),
            'textSize' => $this->getTextSize(),
        ]);
    }

    private function getColor(): string
    {
        return match (true) {
            $this->value >= 80 => 'green',
            $this->value >= 60 => 'blue',
            $this->value >= 40 => 'yellow',
            default => 'red',
        };
    }

    private function getTextSize(): string
    {
        return match ($this->size) {
            'sm' => 'text-xs',
            'lg' => 'text-base',
            default => 'text-sm',
        };
    }
}
