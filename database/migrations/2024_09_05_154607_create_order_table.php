<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_image')->nullable();
            $table->string('amz_order_number');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone_number')->nullable();
            $table->boolean('is_customer_scammer')->default(false);
            $table->decimal('review_type_commission', 8, 2)->default(0);
            $table->enum('status', OrderStatus::toArray())->nullable();
            $table->string('review_image')->nullable();
            $table->string('refund_image')->nullable();
            $table->string('buyer_verification_image')->nullable();
            $table->string('review_link')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('market_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
