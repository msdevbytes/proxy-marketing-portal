<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Reservation extends Pivot
{
    protected $fillable = [
        'reservation_number',
        'keywords',
        'user_id',
        'product_id',
        'market_id',
        'status',
        'reservation_expiry'
    ];

    protected $dates = [
        'created_at',
        'reservation_expiry',
    ];

    protected $casts = [
        'status' => 'boolean',
        'keywords' => 'array',
    ];

    protected $appends = [];

    function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
