<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Club extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'logo_path',
        'budget',
        'user_id',
        'stadium_id',
        'is_active'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'income' => 'decimal:2',
        'expenses' => 'decimal:2',
        'is_active' => 'boolean'
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
            $this->increment('income', $amount);
            $this->increment('budget', $amount);
        } else {
            $this->increment('expenses', $amount);
            $this->decrement('budget', $amount);
        }
    }
}
