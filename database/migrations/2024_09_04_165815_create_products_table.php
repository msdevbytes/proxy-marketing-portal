<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string("image")->nullable();
            $table->string("amazone_image")->nullable();
            $table->string("product_brand")->nullable();
            $table->string("keyword")->nullable();
            $table->string("amz_sold_by")->nullable();
            $table->string("product_link")->nullable();
            $table->string("asin")->nullable();
            $table->string("seller")->nullable();
            $table->integer("sale_limit_per_day")->default(0);
            $table->integer("sale_limit_overall")->default(0);
            $table->integer("commission")->default(0);
            $table->boolean("is_expensive")->default(false);
            $table->decimal("product_price", 8, 2)->default(0);
            $table->string("amazone_short_link")->nullable();
            $table->longText("review_instructions")->nullable();
            $table->longText("refund_conditions")->nullable();
            $table->longText("comission_conditions")->nullable();
            $table->longText("instructions")->nullable();
            $table->date("marketing_end_date")->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('market_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean("status")->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
