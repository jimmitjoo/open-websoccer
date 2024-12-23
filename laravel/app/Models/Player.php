<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Policies\ContractPolicy;

class Player extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'position',
        'club_id',
        'form',
        'strength',
        'stamina',
        'speed',
        'technique',
        'passing',
        'goalkeeper',
        'defense',
        'midfield',
        'striker'
    ];

    protected $casts = [
        'birth_date' => 'datetime',
        'form' => 'integer',
        'strength' => 'integer',
        'stamina' => 'integer',
        'speed' => 'integer',
        'technique' => 'integer',
        'passing' => 'integer',
        'goalkeeper' => 'integer',
        'defense' => 'integer',
        'midfield' => 'integer',
        'striker' => 'integer'
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function activeContract()
    {
        $now = now();
        return $this->hasOne(Contract::class)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
    }

    public function hasActiveContract(): bool
    {
        return $this->activeContract()->exists();
    }

    public function futureContracts()
    {
        return $this->hasMany(Contract::class)
            ->where('start_date', '>', now())
            ->orderBy('start_date');
    }

    public function historicContracts()
    {
        return $this->hasMany(Contract::class)
            ->where('end_date', '<', now())
            ->orderBy('end_date', 'desc');
    }

    public function transferListing()
    {
        return $this->hasOne(TransferListing::class)
            ->where('status', 'active');
    }
}
