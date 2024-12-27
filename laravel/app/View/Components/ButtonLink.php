<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonLink extends Component
{
    public function __construct(
        public string $size = 'md',
        public bool $disabled = false
    ) {}

    public function render()
    {
        return view('components.button-link');
    }
}
