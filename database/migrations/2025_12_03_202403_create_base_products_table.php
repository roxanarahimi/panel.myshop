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
        Schema::create('base_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('made_in')->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->json('images')->nullable();
            $table->integer('price');
            $table->integer('off')->default(0);
            $table->boolean('new')->default(0);
            $table->boolean('visible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_products');
    }
};
