<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YouthAcademyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'monthly_cost',
        'upgrade_cost',
        'max_youth_players',
        'youth_player_generation_rate',
        'min_potential_rating',
        'max_potential_rating',
        'training_efficiency_bonus',
    ];

    protected $casts = [
        'level' => 'integer',
        'monthly_cost' => 'decimal:2',
        'upgrade_cost' => 'decimal:2',
        'max_youth_players' => 'integer',
        'youth_player_generation_rate' => 'integer',
        'min_potential_rating' => 'integer',
        'max_potential_rating' => 'integer',
        'training_efficiency_bonus' => 'integer',
    ];

    public function youthAcademies(): HasMany
    {
        return $this->hasMany(YouthAcademy::class);
    }
}
