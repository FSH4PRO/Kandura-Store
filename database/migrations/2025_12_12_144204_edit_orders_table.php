<?php

use App\Enums\PaymentMethodEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', array_column(PaymentMethodEnum::cases(), 'value'))->default(PaymentMethodEnum::Cod->value)->nullable()->change();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->json('payment_meta')->nullable();
            $table->index(['customer_id', 'status']);
            $table->index(['payment_method', 'status']);
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
