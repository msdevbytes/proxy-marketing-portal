<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'status' => 'boolean',
        'keywords' => 'array',
    ];

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
