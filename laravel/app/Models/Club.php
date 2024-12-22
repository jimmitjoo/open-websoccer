<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'logo_path',
        'budget',
        'league_id',
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

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
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
