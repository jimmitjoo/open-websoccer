<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TrainingType extends Model
{
    use HasFactory;

    protected $casts = [
        'effects' => 'array',
    ];

    protected $fillable = [
        'name',
        'code',
        'effects',
        'intensity'
    ];
}
