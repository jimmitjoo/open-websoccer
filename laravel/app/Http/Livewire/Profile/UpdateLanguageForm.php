<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateLanguageForm extends Component
{
    public $language = '';

    public function mount()
    {
        $this->language = Auth::user()->language;
    }

    public function updateLanguage()
    {
        $this->validate([
            'language' => ['required', 'string', 'in:en,sv'],
        ]);

        Auth::user()->forceFill([
            'language' => $this->language,
        ])->save();

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('profile.update-language-form');
    }
}
