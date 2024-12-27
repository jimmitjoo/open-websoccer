<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YouthPlayerDevelopmentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'youth_player_id',
        'attribute_name',
        'old_value',
        'new_value',
        'development_type',
        'note',
    ];

    protected $casts = [
        'old_value' => 'integer',
        'new_value' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->new_value = min(99, max(1, $log->new_value));
        });

        static::updating(function ($log) {
            $log->new_value = min(99, max(1, $log->new_value));
        });
    }

    public function youthPlayer(): BelongsTo
    {
        return $this->belongsTo(YouthPlayer::class);
    }
}
