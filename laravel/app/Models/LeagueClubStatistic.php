<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeagueClubStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'matches_played',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'clean_sheets',
        'failed_to_score',
        'points',
    ];
}
