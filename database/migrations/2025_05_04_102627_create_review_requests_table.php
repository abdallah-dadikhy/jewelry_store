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
        Schema::create('review_requests', function (Blueprint $table) {
            $table->id('ReviewID');
            $table->unsignedBigInteger('UserID');
            $table->string('ProductName', 100);
            $table->text('ProductDescription');
            $table->decimal('ProductWeight', 10, 2);
            $table->decimal('ProductPrice', 10, 2);
            $table->string('ProductImages')->nullable(); 
            $table->enum('Status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('AdminComments')->nullable();
            $table->timestamps();
            $table->foreign('UserID')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_requests');
    }
};
