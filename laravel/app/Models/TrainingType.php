<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model
{
    protected $casts = [
        'effects' => 'array',
    ];

    protected $fillable = [
        'name',
        'code',
        'effects',
        'intensity',
        'cost'
    ];
} 