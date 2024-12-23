<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    protected $fillable = [
        'name',
        'country_code',
        'level',
        'rank',
        'has_relegation',
        'has_promotion',
        'max_teams',
        'is_active'
    ];

    protected $casts = [
        'rank' => 'integer',
        'has_relegation' => 'boolean',
        'has_promotion' => 'boolean',
        'max_teams' => 'integer',
        'is_active' => 'boolean'
    ];

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(Season::class)
            ->withPivot(['start_date', 'end_date', 'is_completed'])
            ->withTimestamps();
    }

    public function matches(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'league_club_statistics')
            ->withPivot([
                'matches_played',
                'wins',
                'draws',
                'losses',
                'goals_for',
                'goals_against',
                'points',
                'current_position',
                'highest_position',
                'lowest_position',
                'clean_sheets',
                'failed_to_score'
            ])
            ->withTimestamps();
    }
}
