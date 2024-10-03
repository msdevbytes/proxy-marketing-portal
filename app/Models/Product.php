<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "name",
        "image",
        "amazone_image",
        "product_brand",
        "keyword",
        "amz_sold_by",
        "product_link",
        "asin",
        "seller",
        "sale_limit_per_day",
        "sale_limit_overall",
        "commission",
        "is_expensive",
        "product_price",
        "amazone_short_link",
        "review_instructions",
        "refund_conditions",
        "comission_conditions",
        "instructions",
        "marketing_end_date",
        "category_id",
        "market_id",
        "status"
    ];

    protected $casts = [
        "status" => "boolean",
        "keyword" => "array",
        'marketing_end_date' => 'date'
    ];

    protected $dates = [
        'created_at',
        'marketing_end_date'
    ];

    protected $appends = ['reservation_time'];

    static function keywordSeparator(): string
    {
        return ',';
    }

    function isProductDisabledOrMarketingEnd(): bool
    {
        return !$this->status;
    }

    public function getReservationTimeAttribute()
    {
        return $this->created_at->addHours(2);
    }

    function getKeywordsAttribute(): array
    {
        $keywords = $this->attributes['keyword'];

        if (is_null($keywords)) {
            return [];
        }
        $keywords = explode(self::keywordSeparator(), $keywords);

        if (count($keywords) == 1 && empty(str()->replace('"', "",  $keywords[0]))) {
            return [];
        }

        return $keywords;
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    function isOrderOverAllLimitFulfilled(): bool
    {
        return $this->orders()->where('orders.status', '!=', OrderStatus::CANCELLED)->count() < $this->sale_limit_overall;
    }

    function isOrderDailyLimitFulfilled(): bool
    {
        return $this->orders()->where('orders.status', '!=', OrderStatus::CANCELLED)->whereRaw('DATE(orders.created_at) = DATE(?)', Carbon::now())->count() < $this->sale_limit_per_day;
    }


    /**
     * 1. If this product is not reserved by any user then reserve this product
     * 2. If this product is reserved by another user then return error
     * 3. if this product is reserved by current user then show release product
     * 4. if the overall/daily sale limit is fullfulled then don't show any action
     * */
}
