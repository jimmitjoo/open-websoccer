<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Policies\ContractPolicy;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'position',
        'club_id',
        'form',
        'form_trend',
        'matches_played_recently',
        'last_form_update',
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
        'form_trend' => 'float',
        'matches_played_recently' => 'integer',
        'last_form_update' => 'datetime',
        'strength' => 'integer',
        'stamina' => 'integer',
        'speed' => 'integer',
        'technique' => 'integer',
        'passing' => 'integer',
        'goalkeeper' => 'integer',
        'defense' => 'integer',
        'midfield' => 'integer',
        'striker' => 'integer',
        'injured' => 'boolean'
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

    public function injuries()
    {
        return $this->hasMany(Injury::class);
    }

    public function currentInjury()
    {
        return $this->injuries()
            ->whereNull('actual_return_at')
            ->latest('started_at')
            ->first();
    }

    public function isInjured(): bool
    {
        return $this->currentInjury() !== null;
    }

    public function scopeInjured($query)
    {
        return $query->whereHas('injuries', function ($q) {
            $q->whereNull('actual_return_at');
        });
    }

    public function scopeHealthy($query)
    {
        return $query->whereDoesntHave('injuries', function ($q) {
            $q->whereNull('actual_return_at');
        });
    }

    public function formUpdates()
    {
        return $this->hasMany(FormUpdate::class);
    }
}
