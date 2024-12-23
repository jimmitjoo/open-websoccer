<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'league_id',
        'season_id',
        'home_club_id',
        'away_club_id',
        'stadium_id',
        'matchday',
        'scheduled_at',
        'home_score',
        'away_score',
        'type',
        'status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function homeClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function awayClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}