<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TransferOfferStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TransferOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_listing_id',
        'bidding_club_id',
        'amount',
        'exchange_player_1_id',
        'exchange_player_2_id',
        'message',
        'status',
        'deadline'
    ];

    protected $casts = [
        'status' => TransferOfferStatus::class,
        'deadline' => 'datetime',
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
