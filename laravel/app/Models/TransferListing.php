<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferListing extends Model
{
    protected $fillable = [
        'player_id',
        'club_id',
        'asking_price',
        'status',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(TransferOffer::class);
    }
}
