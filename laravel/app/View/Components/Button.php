<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $type = 'button',
        public string $size = 'md',
        public string $tag = 'button',
        public ?bool $disabled = false
    ) {}

    public function render()
    {
        return view('components.button');
    }
}
