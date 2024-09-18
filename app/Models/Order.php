<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
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


    public static function orderStatusByRole(): array
    {
        $status = [];
        $rolesBaseStatus = collect([
            'PM' => [
                OrderStatus::ORDERED->value,
                OrderStatus::CANCELLED->value,
                OrderStatus::REVIEWED->value
            ],
            'PMM' => [
                OrderStatus::REFUNDED->value,
                OrderStatus::DELIVERED->value,
                OrderStatus::ONHOLD->value,
                OrderStatus::CANCELLED->value
            ]

        ]);

        foreach (OrderStatus::cases() as $case) {
            if (in_array($case->value, $rolesBaseStatus->get(Auth::user()->getRoleNames()[0]))) {
                $status[$case->value] = $case->value;
            }
        }
        return $status;
    }
}
