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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id('FavoriteID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('ProductID');
            $table->dateTime('AddedDate')->useCurrent();
            $table->timestamps();
            $table->foreign('UserID')->references('UserID')->on('users');
            $table->foreign('ProductID')->references('ProductID')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
