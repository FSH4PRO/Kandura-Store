<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained('designs')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('sizes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['design_id', 'size_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('design_size');
    }
};
