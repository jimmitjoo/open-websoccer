<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserSettingsController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|in:en,sv',
            'settings' => 'array'
        ]);

        $user = auth()->user();
        $user->update($validated);

        return redirect()->back()->with('success', 'Inställningar uppdaterade');
    }
}
