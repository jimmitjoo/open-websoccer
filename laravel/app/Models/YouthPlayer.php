<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YouthPlayer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'youth_academy_id',
        'first_name',
        'last_name',
        'preferred_position',
        'promotion_available_at',
        'last_training_at',
        // Attribut
        'strength',
        'speed',
        'technique',
        'passing',
        'shooting',
        'heading',
        'tackling',
        'ball_control',
        'stamina',
        'keeper_ability',
        // Personlighetsattribut
        'determination',
        'work_rate',
        'leadership',
    ];

    protected $casts = [
        'promotion_available_at' => 'datetime',
        'last_training_at' => 'datetime',
    ];

    public function youthAcademy(): BelongsTo
    {
        return $this->belongsTo(YouthAcademy::class);
    }

    public function developmentLogs(): HasMany
    {
        return $this->hasMany(YouthPlayerDevelopmentLog::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
