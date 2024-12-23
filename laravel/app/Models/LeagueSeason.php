<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueSeason extends Model
{
    protected $table = 'league_season';

    protected $fillable = [
        'league_id',
        'season_id',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
