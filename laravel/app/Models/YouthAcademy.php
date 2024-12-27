<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class YouthAcademy extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'youth_academy_level_id',
        'next_youth_player_available_at',
        'total_investment',
    ];

    protected $casts = [
        'next_youth_player_available_at' => 'datetime',
        'total_investment' => 'decimal:2',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(YouthAcademyLevel::class, 'youth_academy_level_id');
    }

    public function youthPlayers(): HasMany
    {
        return $this->hasMany(YouthPlayer::class);
    }

    public function hasAvailableYouthPlayer(): bool
    {
        return $this->next_youth_player_available_at <= now() &&
               $this->youthPlayers()->count() < $this->level->max_youth_players;
    }
}
