<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'club_id',
        'strength',
        'form',
        'stamina',
        'speed',
        'technique',
        'passing',
        'goalkeeper',
        'defense',
        'midfield',
        'striker',
        'birth_date',
        'position',
        'injured',
        'injury_days'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'injured' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class)->where('active', true);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
