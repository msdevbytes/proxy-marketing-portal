<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
    ];

    protected $dates = [
        'created_at',
    ];

    protected $appends = ['reservation_time'];

    static function keywordSeparator(): string
    {
        return ',';
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
}
