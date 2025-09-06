<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // حذف العمود Price
            $table->dropColumn('Price');

            // إضافة العمود Karat
            $table->double('Karat', 8, 2)->after('Weight'); 
            // (after => فقط لترتيب الأعمدة، اختياري)
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // استرجاع العمود Price
            $table->decimal('Price', 10, 2);

            // حذف العمود Karat
            $table->dropColumn('Karat');
        });
    }
};
