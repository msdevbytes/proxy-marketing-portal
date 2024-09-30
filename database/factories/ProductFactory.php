<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "name" => fake()->title(),
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
    }
}
