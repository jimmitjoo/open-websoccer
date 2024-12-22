<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    protected $table = 'stadiums';

    protected $fillable = [
        'name',
        'capacity_seats',
        'capacity_stands',
        'capacity_vip',
        'level_pitch',
        'level_seats',
        'level_stands',
        'level_vip',
        'maintenance_pitch',
        'maintenance_facilities',
        'price_seats',
        'price_stands',
        'price_vip'
    ];
}
