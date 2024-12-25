<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Injury extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'match_id',
        'injury_type_id',
        'started_at',
        'expected_return_at',
        'actual_return_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'actual_return_at' => 'datetime'
    ];
}
