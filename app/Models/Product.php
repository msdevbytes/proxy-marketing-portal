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
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
