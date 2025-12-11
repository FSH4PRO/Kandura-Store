<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->json('name');
            $table->json('description')->nullable();
            $table->decimal('price', 10, 2);
           
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
