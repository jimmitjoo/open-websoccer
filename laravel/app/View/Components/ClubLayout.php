<?php

namespace App\View\Components;

use App\Models\Club;
use Illuminate\View\Component;
use Illuminate\View\View;

class ClubLayout extends Component
{
    public function __construct(
        public Club $club
    ) {}

    public function render(): View
    {
        return view('layouts.club');
    }
}
