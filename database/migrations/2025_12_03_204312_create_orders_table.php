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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('type')->default('cart');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->cascadeOnDelete();
            $table->integer('total_amount')->default(0);
            $table->integer('total_off')->default(0);
            $table->integer('delivery_amount')->default(99000);
            $table->integer('amount')->default(0);
            $table->string('status')->default('cart');
            $table->dateTime('payed_at')->nullable();
            $table->string('post_tracking_number')->nullable();

            $table->timestamps();
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
