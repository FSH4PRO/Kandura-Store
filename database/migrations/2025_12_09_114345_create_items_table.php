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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            $table->foreignId('design_id')
                ->constrained('designs')
                ->onDelete('restrict');

            $table->foreignId('size_id')
                ->nullable()
                ->constrained('sizes')
                ->nullOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
