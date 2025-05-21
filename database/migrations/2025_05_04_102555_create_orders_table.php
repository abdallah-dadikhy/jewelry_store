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
            $table->id('OrderID');
            $table->unsignedBigInteger('UserID');
            $table->dateTime('OrderDate');
            $table->enum('Status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->decimal('TotalAmount', 10, 2);
            $table->text('ShippingAddress');
            $table->string('PaymentMethod', 50);
            $table->timestamps();
            $table->foreign('UserID')->references('UserID')->on('users');

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
