<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'old_value', 'new_value', 'adjusted_by', 'reason'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function getFormChange(): int
    {
        return $this->new_value - $this->old_value;
    }

    public function getFormChangePercentage(): float
    {
        return ($this->new_value - $this->old_value) / $this->old_value * 100;
    }
}
