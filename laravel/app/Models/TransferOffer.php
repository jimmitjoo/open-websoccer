<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferOffer extends Model
{
    protected $fillable = [
        'transfer_listing_id',
        'bidding_club_id',
        'amount',
        'exchange_player_1_id',
        'exchange_player_2_id',
        'message',
        'status'
    ];

    public function transferListing(): BelongsTo
    {
        return $this->belongsTo(TransferListing::class);
    }

    public function bidderClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'bidding_club_id');
    }
}
