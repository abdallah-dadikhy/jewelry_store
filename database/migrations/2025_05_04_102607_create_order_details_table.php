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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('OrderDetailID');
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('ProductID');
            $table->integer('Quantity');
            $table->decimal('PriceAtPurchase', 10, 2);
            $table->timestamps();
            $table->foreign('OrderID')->references('OrderID')->on('orders');
            $table->foreign('ProductID')->references('ProductID')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
