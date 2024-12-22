<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubTransaction extends Model
{
    protected $fillable = [
        'club_id',
        'description',
        'amount',
        'type', // 'income' eller 'expense'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
