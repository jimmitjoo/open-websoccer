<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TransferListingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'club_id',
        'asking_price',
        'status',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'status' => TransferListingStatus::class,
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
