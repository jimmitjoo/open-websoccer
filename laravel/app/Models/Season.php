<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Season extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class)
            ->withPivot(['start_date', 'end_date', 'is_completed'])
            ->withTimestamps();
    }
}
