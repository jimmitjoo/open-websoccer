<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Services\PlayerGenerationService;

class Club extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'logo_path',
        'user_id',
        'stadium_id',
        'is_active',
        'balance'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class, 'league_club_statistics')
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
                'failed_to_score',
                'season_id'
            ])
            ->withTimestamps();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ClubTransaction::class);
    }

    public function addTransaction(string $description, float $amount, string $type): void
    {
        $this->transactions()->create([
            'description' => $description,
            'amount' => $amount,
            'type' => $type
        ]);

        if ($type === 'income') {
            $this->increment('balance', $amount);
        } else {
            $this->decrement('balance', $amount);
        }
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function canAfford(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function charge(float $amount): void
    {
        if (!$this->canAfford($amount)) {
            throw new \Exception('Klubben har inte råd med denna transaktion.');
        }

        $this->balance -= $amount;
        $this->save();
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function getIncomeAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'income')
            ->sum('amount');
    }

    public function getExpensesAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->sum('amount');
    }

    public function getBudgetAttribute(): float
    {
        return $this->balance + $this->income - $this->expenses;
    }

    protected static function booted()
    {
        static::created(function ($club) {
            app(PlayerGenerationService::class)->generatePlayersForClub($club);
        });
    }
}
