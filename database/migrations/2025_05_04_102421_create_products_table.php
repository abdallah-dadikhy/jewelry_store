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
                $table->id('ProductID');
                $table->string('Name', 100);
                $table->text('Description');
                $table->decimal('Weight', 10, 2);
                $table->decimal('Price', 10, 2);
                $table->string('ProductFile');
                $table->boolean('IsFeatured')->default(false);
                $table->boolean('IsAvailable')->default(true);
                
                $table->timestamps();
                
                $table->unsignedBigInteger('CategoryID');
                
                $table->foreign('CategoryID')->references('CategoryID')->on('product_categories');
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
