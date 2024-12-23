<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Player;
use App\Models\Club;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferHistory extends Model
{
    protected $table = 'transfer_history';

    protected $fillable = [
        'player_id',
        'from_club_id',
        'to_club_id',
        'amount',
        'fee',
        'type',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function fromClub()
    {
        return $this->belongsTo(Club::class, 'from_club_id');
    }

    public function toClub()
    {
        return $this->belongsTo(Club::class, 'to_club_id');
    }
}
