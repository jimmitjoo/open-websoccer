<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeagueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'size:2'],
            'level' => ['required', 'string', 'in:national,continental'],
            'rank' => ['required', 'integer', 'min:1'],
            'max_teams' => ['required', 'integer', 'min:1'],
            'has_relegation' => ['boolean'],
            'has_promotion' => ['boolean'],
            'is_active' => ['boolean'],
            'seasons' => ['array'],
            'seasons.*' => ['exists:seasons,id'],
        ];
    }
}
