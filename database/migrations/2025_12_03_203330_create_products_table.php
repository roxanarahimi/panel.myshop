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
            $table->foreignId('base_product_id')->constrained('base_products')->cascadeOnDelete();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('price')->default(0);
            $table->integer('off')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('sale')->default(0);
            $table->boolean('visible')->default(0);
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
