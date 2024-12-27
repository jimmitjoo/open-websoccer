<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FormUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'old_value',
        'new_value',
        'reason',
        'adjusted_by',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
