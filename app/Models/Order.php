<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Pivot
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'invoice_image',
        'amz_order_number',
        'customer_email',
        'customer_phone_number',
        'is_customer_scammer',
        'review_type_commission',
        'status',
        'review_image',
        'refund_image',
        'buyer_verification_image',
        'review_link',
        'remarks',
        'market_id',
        'user_id',
        'product_id'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
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
