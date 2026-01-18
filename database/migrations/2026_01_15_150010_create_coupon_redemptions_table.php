<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->decimal('discount_amount', 12, 2);
            $table->timestamp('redeemed_at');

            $table->timestamps();

            // كل مستخدم يستخدم نفس الكوبون مرة واحدة
            $table->unique(['coupon_id', 'customer_id']);

            // كوبون واحد لكل order
            $table->unique(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
